<?php
namespace Cotizador\Service;

use Cotizador\Entity\BankAccount;
use Cotizador\Entity\Category;
use Cotizador\Entity\Contact;
use Cotizador\Entity\Currency;
use Cotizador\Entity\Supplier;

/**
 * This service is responsible for adding/editing suppliers
 * and changing supplier status.
 */
class SupplierManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * This method adds a new supplier.
     * @param array $data
     * @return Supplier
     */
    public function addSupplier($data)
    {
        // Create new Supplier entity.
        $supplier = new Supplier();
        $supplier->setName($data['name']);
        $supplier->setTaxAddress($data['taxAddress']);
        $supplier->setRfc($data['rfc']);
        $supplier->setPaymentTerms($data['paymentTerms']);
        $supplier->setCreditLimit($data['creditLimit']);
        $supplier->setDiscount($data['discount']);
        $supplier->setDeliveryTime($data['deliveryTime']);
        $supplier->setComments($data['comments']);
        $supplier->setStatus(Supplier::STATUS_ACTIVE);

        // Add contacts to supplier
        $this->assignContacts($supplier, $data['contacts']);

        // Add bank accounts to supplier.
        $this->assignBankAccounts($supplier, $data['bankAccounts']);

        // Assign categories to supplier
        $this->assignCategories($supplier, $data['categories']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($supplier);

        // Apply changes to database.
        $this->entityManager->flush();

        return $supplier;
    }

    /**
     * This method updates data of an existing supplier.
     * @param Supplier $supplier
     * @param array $data
     * @return boolean
     */
    public function updateSupplier($supplier, $data)
    {
        $supplier->setName($data['name']);
        $supplier->setTaxAddress($data['taxAddress']);
        $supplier->setRfc($data['rfc']);
        $supplier->setPaymentTerms($data['paymentTerms']);
        $supplier->setCreditLimit($data['creditLimit']);
        $supplier->setDiscount($data['discount']);
        $supplier->setDeliveryTime($data['deliveryTime']);
        $supplier->setComments($data['comments']);

        // Add contacts to supplier
        $this->assignContacts($supplier, $data['contacts']);

        // Add bank accounts to supplier.
        $this->assignBankAccounts($supplier, $data['bankAccounts']);

        // Assign categories to supplier
        $this->assignCategories($supplier, $data['categories']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * A helper method which assign new categories to the supplier
     * @param Cotizador\Entity\Supplier $supplier
     * @param array $categoryIds
     * @return void
     */
    private function assignCategories($supplier, $categoryIds)
    {
        // Remove old supplier category(s).
        $supplier->getCategories()->clear();

        // Assign new category(s).
        foreach ($categoryIds as $categoryId) {
            $category = $this->entityManager->getRepository(Category::class)
                ->find($categoryId);
            if (null == $category) {
                throw new \Exception('Not found category by ID');
            }

            $supplier->addCategory($category);
        }
    }

    /**
     * A helper method which manage bank account's supplier.
     * @param Cotizador\Entity\Supplier $supplier
     * @param string $bankAccountJson JSON string
     * @return void
     */
    private function assignBankAccounts($supplier, $bankAccountJson)
    {
        // Preserve id to edit
        $bankAccountsIdsToEdit = [];

        // Get all bank accounts to find elements to delete.
        $bankAccounts = $supplier->getBankAccountsAsArray();

        // Decode JSON string to asossiative array.
        $bankAccountsData = json_decode($bankAccountJson, true);

        // If decoding is correct, assign bank accounts.
        if (null != $bankAccountsData) {
            foreach ($bankAccountsData as $bankAccountData) {
                if (isset($bankAccountData['id']) && $bankAccountData['id'] > 0) {
                    $bankAccount = $this->entityManager->getRepository(BankAccount::class)
                        ->find((int)$bankAccountData['id']);
                    if (null == $bankAccount) {
                        throw new \Exception('Not found bank account by ID');
                    }
                    $bankAccountsIdsToEdit[] = (int)$bankAccountData['id'];
                    $this->editBankAccount($bankAccount, $bankAccountData);
                } else {
                    $bankAccount = $this->addBankAccount($bankAccountData);
                    $this->entityManager->persist($bankAccount);
                    $supplier->addBankAccount($bankAccount);
                }
            }
        }

        // Verify each bank account to remove.
        foreach ($bankAccounts as $bankAccountSaved) {
            if (false === array_search((int)$bankAccountSaved['id'], $bankAccountsIdsToEdit)) {
                $bankAccount = $this->entityManager->getRepository(BankAccount::class)
                        ->find((int)$bankAccountSaved['id']);
                if (null == $bankAccount) {
                    throw new \Exception('Not found bank account by ID');
                }
                $this->entityManager->remove($bankAccount);
                $supplier->removeBankAccount($bankAccount);
            }
        }
    }

    /**
     * A helper method which add bank accounts to the supplier.
     * @param array $data
     * @return Cotizador\Entity\BankAccount
     */
    private function addBankAccount($data)
    {
        $bankAccount = new BankAccount();
        $bankAccount->setBank($data['bank']);
        $bankAccount->setNumber($data['number']);
        $bankAccount->setClabe($data['clabe']);

        // Find currency
        $currency = $this->entityManager->getRepository(Currency::class)
            ->find((int)$data['currency']['id']);
        if (null == $currency) {
            throw new \Exception('Not found currency by ID');
        }
        $bankAccount->setCurrency($currency);

        return $bankAccount;
    }

    /**
     * A helper method which edit bank account.
     * @param Cotizador\Entity\BankAccount $bankAccount
     * @param array $data
     * @return boolean
     */
    private function editBankAccount($bankAccount, $data)
    {
        $bankAccount->setBank($data['bank']);
        $bankAccount->setNumber($data['number']);
        $bankAccount->setClabe($data['clabe']);

        // Find currency
        $currency = $this->entityManager->getRepository(Currency::class)
            ->find((int)$data['currency']['id']);
        if (null == $currency) {
            throw new \Exception('Not found currency by ID');
        }
        $bankAccount->setCurrency($currency);

        return true;
    }

    /**
     * A helper method which manage contact's supplier.
     * @param Cotizador\Entity\Supplier $supplier
     * @param string $contactJson JSON string
     * @return void
     */
    private function assignContacts($supplier, $contactJson)
    {
        // Preserve id to edit
        $contactsIdsToEdit = [];

        // Get all contacts to find elements to delete.
        $contacts = $supplier->getContactsAsArray();

        // Decode JSON string to assosiative array.
        $contactsData = json_decode($contactJson, true);

        // If decoding is correct, assign contacts.
        if (null != $contactsData) {
            foreach ($contactsData as $contactData) {
                if (isset($contactData['id']) && $contactData['id'] > 0) {
                    $contact = $this->entityManager->getRepository(Contact::class)
                        ->find((int)$contactData['id']);
                    if (null == $contact) {
                        throw new \Exception('Not found contact by ID');
                    }
                    $contactsIdsToEdit[] = (int)$contactData['id'];
                    $this->editContact($contact, $contactData);
                } else {
                    $contact = $this->addContact($contactData);
                    $this->entityManager->persist($contact);
                    $supplier->addContact($contact);
                }
            }
        }

        // Verify each contact to remove.
        foreach ($contacts as $contactSaved) {
            if (false === array_search((int)$contactSaved['id'], $contactsIdsToEdit)) {
                $contact = $this->entityManager->getRepository(Contact::class)
                    ->find((int)$contactSaved['id']);
                if (null == $contact) {
                    throw new \Exception('Not found contact by ID');
                }
                $this->entityMnager->remove($contact);
                $supplier->removeContact($contact);
            }
        }
    }

    /**
     * A helper method add contact to the supplier.
     * @param array $data
     * @return Cotizador\Entity\Contact
     */
    private function addContact($data)
    {
        $contact = new Contact();
        $contact->setName($data['name']);
        $contact->setPhoneNumber($data['phoneNumber']);
        $contact->setEmail($data['email']);
        $contact->setJob($data['job']);

        return $contact;
    }

    /**
     * A helper method which edit contact.
     * @param Cotizador\Entity\Contact $contact
     * @param array $data
     * @return boolean
     */
    private function editContact($contact, $data)
    {
        $contact->setName($data['name']);
        $contact->setPhoneNumber($data['phoneNumber']);
        $contact->setEmail($data['email']);
        $contact->setJob($data['job']);

        return true;
    }
}
