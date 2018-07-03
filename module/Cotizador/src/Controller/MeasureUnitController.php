<?php
namespace Cotizador\Controller;

use Cotizador\Entity\MeasureUnit;
use Cotizador\Form\MeasureUnitForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for measure unit management (adding, editing,
 * viewing and delete brand).
 */
class MeasureUnitController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Measure unit manager.
     * @var Cotizador\Service\MeasureUnitService
     */
    private $measureUnitManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $measureUnitManager)
    {
        $this->entityManager      = $entityManager;
        $this->measureUnitManager = $measureUnitManager;
    }

    /**
     * This is the default "index" action of the controller. It display the
     * list of measure units.
     */
    public function indexAction()
    {
        $measureUnits = $this->entityManager->getRepository(MeasureUnit::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'measureUnits' => $measureUnits,
        ]);
    }

    /**
     * This action display a page allowing to add a new measure unit.
     */
    public function addAction()
    {
        // Create measure unit form.
        $form = new MeasureUnitForm($this->entityManager);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data.
                $data = $form->getData();

                // Add measure unit.
                $measureUnit = $this->measureUnitManager->addMeasureUnit($data);

                // Redirect to "index" page.
                return $this->redirect()->toRoute(
                    'measureUnits',
                    ['action' => 'index']
                );
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * The "view" action display page allowing to view measure unit's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a measure unit with such ID.
        $measureUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->find($id);

        if (null == $measureUnit) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'measureUnit' => $measureUnit,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit measure unit.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $measureUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->find($id);

        if (null == $measureUnit) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create measure unit form.
        $form = new MeasureUnitForm($this->entityManager, $measureUnit);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // get filtered and validated data.
                $data = $form->getData();

                // Update the measure unit.
                $this->measureUnitManager->updateMeasureUnit($measureUnit, $data);

                // Redirect to "index" page.
                return $this->redirect()->toRoute(
                    'measureUnits',
                    ['action' => 'index']
                );
            }
        } else {
            $form->setData([
                'name'          => $measureUnit->getName(),
                'code'          => $measureUnit->getCode(),
                'description'   => $measureUnit->getDescription(),
            ]);
        }

        return new ViewModel([
            'measureUnit' => $measureUnit,
            'form'  => $form,
        ]);
    }

    /**
     * The "delete" action allow to delete a measure unit.
     * Only can delete if brand is not assigned.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $measureUnit = $this->entityManager->getRepository(MeasureUnit::class)
            ->find($id);

        if (null == $measureUnit) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del', 'No');

            if ($del == 'Si') {
                $this->measureUnitManager->deleteMeasureUnit($measureUnit);
            }

            // Redirect to "index" page.
            return $this->redirect()->toRoute(
                'measureUnits',
                ['action' => 'index']
            );
        }

        return new ViewModel([
            'measureUnit' => $measureUnit,
        ]);
    }
}
