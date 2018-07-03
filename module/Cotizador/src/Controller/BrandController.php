<?php
namespace Cotizador\Controller;

use Cotizador\Entity\Brand;
use Cotizador\Form\BrandForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for brand management (adding, editing,
 * viewing and delete brand).
 */
class BrandController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Brand manager.
     * @var Cotizador\Service\BrandService
     */
    private $brandManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $brandManager)
    {
        $this->entityManager = $entityManager;
        $this->brandManager  = $brandManager;
    }

    /**
     * This is the default "index" action of the controller. It display the
     * list of brands.
     */
    public function indexAction()
    {
        $brands = $this->entityManager->getRepository(Brand::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'brands' => $brands,
        ]);
    }

    /**
     * This action display a page allowing to add a new brand.
     */
    public function addAction()
    {
        // Create brand form.
        $form = new BrandForm($this->entityManager);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data.
                $data = $form->getData();

                // Add brand.
                $brand = $this->brandManager->addBrand($data);

                // Redirect to "index" page.
                return $this->redirect()->toRoute(
                    'brands',
                    ['action' => 'index']
                );
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * The "view" action display page allowing to view brand's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a brand with such ID.
        $brand = $this->entityManager->getRepository(Brand::class)
            ->find($id);

        if (null == $brand) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'brand' => $brand,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit brand.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $brand = $this->entityManager->getRepository(Brand::class)
            ->find($id);

        if (null == $brand) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create brand form.
        $form = new BrandForm($this->entityManager, $brand);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // get filtered and validated data.
                $data = $form->getData();

                // Update the brand.
                $this->brandManager->updateBrand($brand, $data);

                // Redirect to "index" page.
                return $this->redirect()->toRoute(
                    'brands',
                    ['action' => 'index']
                );
            }
        } else {
            $form->setData([
                'name'          => $brand->getName(),
                'description'   => $brand->getDescription(),
            ]);
        }

        return new ViewModel([
            'brand' => $brand,
            'form'  => $form,
        ]);
    }

    /**
     * The "delete" action allow to delete a brand.
     * Only can delete if brand is not assigned.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $brand = $this->entityManager->getRepository(Brand::class)
            ->find($id);

        if (null == $brand) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del', 'No');

            if ($del == 'Si') {
                $this->brandManager->deleteBrand($brand);
            }

            // Redirect to "index" page.
            return $this->redirect()->toRoute(
                'brands',
                ['action' => 'index']
            );
        }

        return new ViewModel([
            'brand' => $brand,
        ]);
    }
}
