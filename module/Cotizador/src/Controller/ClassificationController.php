<?php
namespace Cotizador\Controller;

use Cotizador\Entity\Classification;
use Cotizador\Form\ClassificationForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for classification management (adding, editing,
 * viewing and delete classification).
 */
class ClassificationController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Classification manager.
     * @var Cotizador\Service\ClassificationService
     */
    private $classificationManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $classificationManager)
    {
        $this->entityManager         = $entityManager;
        $this->classificationManager = $classificationManager;
    }

    /**
     * This is the default "index" action of the controller. It display the
     * list of classifications.
     */
    public function indexAction()
    {
        $classifications = $this->entityManager->getRepository(Classification::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'classifications' => $classifications,
        ]);
    }

    /**
     * This action display a page allowing to add a new classification.
     */
    public function addAction()
    {
        // Create classification form.
        $form = new ClassificationForm($this->entityManager);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data.
                $data = $form->getData();

                // Add classification.
                $classification = $this->classificationManager->addClassification($data);

                // Redirect to "index" page.
                return $this->redirect()->toRoute(
                    'classifications',
                    ['action' => 'index']
                );
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * The "view" action display page allowing to view classification's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a classification with such ID.
        $classification = $this->entityManager->getRepository(Classification::class)
            ->find($id);

        if (null == $classification) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'classification' => $classification,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit classification.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $classification = $this->entityManager->getRepository(Classification::class)
            ->find($id);

        if (null == $classification) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create classification form.
        $form = new ClassificationForm($this->entityManager, $classification);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // get filtered and validated data.
                $data = $form->getData();

                // Update the classification.
                $this->classificationManager->updateClassification($classification, $data);

                // Redirect to "index" page.
                return $this->redirect()->toRoute(
                    'classifications',
                    ['action' => 'index']
                );
            }
        } else {
            $form->setData([
                'name'          => $classification->getName(),
                'description'   => $classification->getDescription(),
            ]);
        }

        return new ViewModel([
            'classification' => $classification,
            'form'           => $form,
        ]);
    }

    /**
     * The "delete" action allow to delete a classification.
     * Only can delete if classification is not assigned.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $classification = $this->entityManager->getRepository(Brand::class)
            ->find($id);

        if (null == $classification) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del', 'No');

            if ($del == 'Si') {
                $this->classificationManager->deleteClassification($classification);
            }

            // Redirect to "index" page.
            return $this->redirect()->toRoute(
                'classifications',
                ['action' => 'index']
            );
        }

        return new ViewModel([
            'classification' => $classification,
        ]);
    }
}
