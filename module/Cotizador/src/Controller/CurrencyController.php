<?php
namespace Cotizador\Controller;

use Cotizador\Entity\Currency;
use Cotizador\Form\CurrencyForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is resposible for currency management (adding, editing,
 * viewing currency).
 */
class CurrencyController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This is the default "index" action of the controller. It display the
     * list of currrencies.
     */
    public function indexAction()
    {
        $currencies = $this->entityManager->getRepository(Currency::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'currencies' => $currencies,
        ]);
    }

    /**
     * This action display a page allowing to add a new currency.
     */
    public function addAction()
    {
        // Create currency form.
        $form = new CurrencyForm();

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();
            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data.
                $data = $form->getData();

                // Add currency.
                // Create new Currency entity.
                $currency = new Currency();
                $currency->setId($data['id']);
                $currency->setName($data['name']);
                $currency->setCode($data['code']);
                $currency->setSymbol($data['symbol']);

                // Add Currency entity to entity manager.
                $this->entityManager->persist($currency);

                // Apply changes to database.
                $this->entityManager->flush();

                //Redirect to "index" page.
                return $this->redirect()->toRoute('currencies');
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    /**
     * The "view" action display page allowing to view currency's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a currency with such ID.
        $currency = $this->entityManager->getRepository(Currency::class)
            ->find($id);

        if (null == $currency) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'currency' => $currency,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit currency.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $currency = $this->entityManager->getRepository(Currency::class)
            ->find($id);

        if (null == $currency) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create currency form.
        $form = new CurrencyForm();

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();
            $data['id'] = $currency->getId();
            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data.
                $data = $form->getData();

                // Update the currency.
                $currency->setName($data['name']);
                $currency->setCode($data['code']);
                $currency->setSymbol($data['symbol']);

                // Apply changes yo database.
                $this->entityManager->flush();

                // Redirect to "index" page.
                return $this->redirect()->toRoute('currencies');
            }
        } else {
            $form->setData([
                'name'  => $currency->getName(),
                'code'  => $currency->getCode(),
                'symbol' => $currency->getSymbol(),
            ]);
        }

        return new ViewModel([
            'currency' => $currency,
            'form'     => $form,
        ]);
    }

    /**
     * The "delete" action allow to delete a currency.
     */
    public function deleteAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $currency = $this->entityManager->getRepository(Currency::class)
            ->find($id);
        if (null == $currency) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del', 'No');

            if ($del == 'Si') {
                $this->entityManager->remove($currency);

                // Apply changes to database.
                $this->entityManager->flush();
            }

            // Redirect to "index" page.
            return $this->redirect()->toRoute('currencies');
        }

        return new ViewModel([
            'currency' => $currency,
        ]);
    }
}
