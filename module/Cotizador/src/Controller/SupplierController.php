<?php

namespace Cotizador\Controller;

use Cotizador\Entity\Category;
use Cotizador\Entity\Currency;
use Cotizador\Entity\Supplier;
use Cotizador\Form\BankAccountForm;
use Cotizador\Form\ContactForm;
use Cotizador\Form\SupplierForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is responsible for supplier management (adding, editing,
 * viewing user).
 */
class SupplierController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Supplier manager.
     * @var Cotizador\Service\SupplierManager
     */
    private $supplierManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $supplierManager)
    {
        $this->entityManager   = $entityManager;
        $this->supplierManager = $supplierManager;
    }

    /**
     * This is the default "index" action of the controller. It display the
     * list of suppliers.
     */
    public function indexAction()
    {
        $suppliers = $this->entityManager->getRepository(Supplier::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'suppliers' => $suppliers,
        ]);
    }

    /**
     * This action display a page allowing to add a new supplier.
     */
    public function addAction()
    {
        // Create supplier form.
        $form = new SupplierForm();

        // Get the list of all availables categories (sorted by name).
        $allCategories = $this->entityManager->getRepository(Category::class)
            ->findBy([], ['name' => 'ASC']);

        $categoryList = [];
        foreach ($allCategories as $category) {
            $categoryList[$category->getId()] = $category->getName();
        }

        $form->get('categories')->setValueOptions($categoryList);

        // Create bank account form.
        $formBankAccount = new BankAccountForm();

        // Get the list of all availables currencies (sorted by code).
        $allCurrencies = $this->entityManager->getRepository(Currency::class)
            ->findBy([], ['code' => 'ASC']);

        $currencyList = [];
        foreach ($allCurrencies as $currency) {
            $currencyList[$currency->getId()] = $currency->getCode();
        }

        $formBankAccount->get('currency')->setValueOptions($currencyList);

        // Create contact form.
        $formContact = new ContactForm();

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form
            if ($form->isValid()) {
                // Get filtered and validated data
                $data = $form->getData();

                // Add supplier.
                $supplier = $this->supplierManager->addSupplier($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute(
                    'suppliers',
                    ['action' => 'view', 'id' => $supplier->getId()]
                );
            }
        }

        return new ViewModel([
            'form' => $form,
            'formBankAccount' => $formBankAccount,
            'formContact'     => $formContact,
        ]);
    }

    /**
     * The "view" action display page allowing to view supplier' details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a supplier with such ID.
        $supplier = $this->entityManager->getRepository(Supplier::class)
            ->find($id);

        if (null == $supplier) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'supplier' => $supplier,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit supplier.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $supplier = $this->entityManager->getRepository(Supplier::class)
            ->find($id);

        if (null == $supplier) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create supplier form.
        $form = new SupplierForm();

        // Get the list of all availables categories (sorted by name).
        $allCategories = $this->entityManager->getRepository(Category::class)
            ->findBy([], ['name' => 'ASC']);

        $categoryList = [];
        foreach ($allCategories as $category) {
            $categoryList[$category->getId()] = $category->getName();
        }

        $form->get('categories')->setValueOptions($categoryList);

        // Create bank account form.
        $formBankAccount = new BankAccountForm();

        // Get the list of all availables currencies (sorted by code).
        $allCurrencies = $this->entityManager->getRepository(Currency::class)
            ->findBy([], ['code' => 'ASC']);

        $currencyList = [];
        foreach ($allCurrencies as $currency) {
            $currencyList[$currency->getId()] = $currency->getCode();
        }

        $formBankAccount->get('currency')->setValueOptions($currencyList);

        // Create contact form.
        $formContact = new ContactForm();

        // Check if user has submitted the form.
        if ($this->getRequest()->isPost()) {
            // Fill in the form with POST data.
            $data = $this->params()->fromPost();

            $form->setData($data);

            // Validate form.
            if ($form->isValid()) {
                // Get filtered and validated data.
                $data = $form->getData();

                // Update the supplier.
                $this->supplierManager->updateSupplier($supplier, $data);

                // Redirect to "view" page.
                return $this->redirect()->toRoute(
                    'suppliers',
                    ['action' => 'view', 'id' => $supplier->getId()]
                );
            }
        } else {
            $supplierCategoryIds = [];
            foreach ($supplier->getCategories() as $category) {
                $supplierCategoryIds[] = $category->getId();
            }

            // Encode bank accounts
            $bankAccounts = $supplier->getBankAccounts();
            $bankAccountsArr = [];
            foreach ($bankAccounts as $bankAccount) {
                $tmp = [];
                $tmp['id'] = $bankAccount->getId();
                $tmp['bank'] = $bankAccount->getBank();
                $tmp['number'] = $bankAccount->getNumber();
                $tmp['clabe'] = $bankAccount->getClabe();
                $tmp['currency']['id'] = $bankAccount->getCurrency()->getId();
                $tmp['currency']['code'] = $bankAccount->getCurrency()->getCode();
                $bankAccountsArr[] = $tmp;
            }
            $bankAccountsJson = json_encode($bankAccountsArr);

            // Encode contacts
            $contacts = $supplier->getContacts();
            $contactsArr = [];
            foreach ($contacts as $contact) {
                $tmp = [];
                $tmp['id']          = $contact->getId();
                $tmp['name']        = $contact->getName();
                $tmp['phoneNumber'] = $contact->getPhoneNumber();
                $tmp['email']       = $contact->getEmail();
                $tmp['job']         = $contact->getJob();
                $contactsArr[]      = $tmp;
            }
            $contactsJson = json_encode($contactsArr);

            $form->setData([
                'name'          => $supplier->getName(),
                'categories'    => $supplierCategoryIds,
                'taxAddress'    => $supplier->getTaxAddress(),
                'rfc'           => $supplier->getRfc(),
                'paymentTerms'  => $supplier->getPaymentTerms(),
                'creditLimit'   => $supplier->getCreditLimit(),
                'discount'      => $supplier->getDiscount(),
                'deliveryTime'  => $supplier->getDeliveryTime(),
                'comments'      => $supplier->getComments(),
                'bankAccounts'  => $bankAccountsJson,
                'contacts'      => $contactsJson,
            ]);
        }

        return new ViewModel([
            'supplier'  => $supplier,
            'form'      => $form,
            'formBankAccount' => $formBankAccount,
            'formContact'     => $formContact,
        ]);
    }
}
