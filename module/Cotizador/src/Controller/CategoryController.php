<?php
namespace Cotizador\Controller;

use Cotizador\Entity\Category;
use Cotizador\Form\CategoryForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for category management (adding, editing,
 * viewing category).
 */
class CategoryController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Category manager.
     * @var Cotizador\Service\CategoryManager
     */
    private $categoryManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $categoryManager)
    {
        $this->entityManager   = $entityManager;
        $this->categoryManager = $categoryManager;
    }

    /**
     * This is the dafault "index" action of the controller. It display the
     * list of categories.
     */
    public function indexAction()
    {
        $categories = $this->entityManager->getRepository(Category::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'categories' => $categories,
        ]);
    }

    /**
     * This action display a page allowing to add a new category.
     */
    public function addAction()
    {
        // Create category form.
        $form = new CategoryForm($this->entityManager);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // Add category.
                $category = $this->categoryManager->addCategory($data);

                // Redirect to "index" page
                return $this->redirect()->toRoute(
                    'categories',
                    ['action' => 'index']
                );
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    /**
     * The "view" action display page allowing to view category's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a category with such ID.
        $category = $this->entityManager->getRepository(Category::class)
            ->find($id);

        if (null == $category) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'category' => $category,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit category.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $category = $this->entityManager->getRepository(Category::class)
            ->find($id);

        if (null == $category) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create category form.
        $form = new CategoryForm($this->entityManager, $category);

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data.
                $data = $form->getData();

                // Update the category.
                $this->categoryManager->updateCategory($category, $data);

                // Redirect to "index" page.
                return $this->redirect()->toRoute(
                    'categories',
                    ['action' => 'index']
                );
            }
        } else {
            $form->setData([
                'name'          => $category->getName(),
                'description'   => $category->getDescription(),
            ]);
        }

        return new ViewModel([
            'category'  => $category,
            'form'      => $form,
        ]);
    }

    /**
     * The "delete" action allow to delete a category.
     * Only can delete if category is not assigned.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $category = $this->entityManager->getRepository(Category::class)
            ->find($id);

        if (null == $category) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del', 'No');

            if ($del == 'Si') {
                $this->categoryManager->deleteCategory($category);
            }

            // Redirect to "index" page.
            return $this->redirect()->toRoute(
                'categories',
                ['action' => 'index']
            );
        }

        return new ViewModel([
            'category' => $category,
        ]);
    }
}
