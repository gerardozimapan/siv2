<?php
namespace Cotizador\Service;

use Cotizador\Entity\Contact;
use Cotizador\Entity\Client;

/**
 * This service is responsible for adding/editing clients
 * and changing client status.
 */
class ClientManager
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
     * This method adds a new client.
     * @param array $data
     * @return Cotizador\Entity\Client
     */
    public function addClient($data)
    {
        // Create new Client entity.
        $client = new Client();
        $client->setName($data['name']);
        $client->setTaxAddress($data['taxAddress']);
        $client->setRfc($data['rfc']);
        $client->setPaymentTerms($data['paymentTerms']);
        $client->setCreditLimit($data['creditLimit']);
        $client->setDiscount($data['discount']);
        $client->setComments($data['comments']);
        $client->setStatus(Client::STATUS_ACTIVE);

        // Add contacts to client
        $this->assignContacts($client, $data['contacts']);

        // Add the entity to the entity manager.
        $this->entityManager->persist($client);

        // Apply changes to database.
        $this->entityManager->flush();

        return $client;
    }

    /**
     * This method updates data of an existing client.
     * @param Cotizador\Entity\Client $client
     * @param array $data
     * @return boolean
     */
    public function updateClient($client, $data)
    {
        $client->setName($data['name']);
        $client->setTaxAddress($data['taxAddress']);
        $client->setRfc($data['rfc']);
        $client->setPaymentTerms($data['paymentTerms']);
        $client->setCreditLimit($data['creditLimit']);
        $client->setDiscount($data['discount']);
        $client->setComments($data['comments']);

        // Add contacts to client
        $this->assignContacts($client, $data['contacts']);

        // Apply changes to database.
        $this->entityManager->flush();

        return true;
    }

    /**
     * A helper method which manage contact's client.
     * @param Cotizador\Entity\Client $client
     * @param string $contactJson JSON string
     * @return void
     */
    private function assignContacts($client, $contactJson)
    {
        // Preserve id to edit
        $contactsIdsToEdit = [];

        // Get all contacts to find elements to delete.
        $contacts = $client->getContactsAsArray();

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
                    $client->addContact($contact);
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
                $client->removeContact($contact);
            }
        }
    }

    /**
     * A helper method add contact to the client.
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
