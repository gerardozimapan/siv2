<?php

namespace Cotizador\Controller;

use Cotizador\Entity\Client;
use Cotizador\Form\ContactForm;
use Cotizador\Form\ClientForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is reponsible for client management (adding, editing,
 * viewing client).
 */
class ClientController extends AbstractActionController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * Supplier manager.
     * @var Cotizador\Service\ClientManager
     */
    private $clientManager;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $clientManager)
    {
        $this->entityManager   = $entityManager;
        $this->clientManager = $clientManager;
    }

    /**
     * This is the default "index" action of the controller. It display the
     * list of clients.
     */
    public function indexAction()
    {
        $clients = $this->entityManager->getRepository(Client::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'clients' => $clients,
        ]);
    }

    /**
     * This action display a page allowing to add a new client.
     */
    public function addAction()
    {
        // Create client form.
        $form = new ClientForm();

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

                // Add client.
                $client = $this->clientManager->addClient($data);

                // Redirect to "view" page
                return $this->redirect()->toRoute(
                    'clients',
                    ['action' => 'view', 'id' => $client->getId()]
                );
            }
        }

        return new ViewModel([
            'form' => $form,
            'formContact'     => $formContact,
        ]);
    }

    /**
     * The "view" action display page allowing to view client's details.
     */
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Find a client with such ID.
        $client = $this->entityManager->getRepository(Client::class)
            ->find($id);

        if (null == $client) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        return new ViewModel([
            'client' => $client,
        ]);
    }

    /**
     * The "edit" action display a page allowing to edit client.
     */
    public function editAction()
    {
        $id = (int)$this->params()->fromRoute('id', -1);
        if ($id < 1) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $client = $this->entityManager->getRepository(Client::class)
            ->find($id);

        if (null == $client) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        // Create client form.
        $form = new ClientForm();

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

                // Update the client.
                $this->clientManager->updateClient($client, $data);

                // Redirect to "view" page.
                return $this->redirect()->toRoute(
                    'clients',
                    ['action' => 'view', 'id' => $client->getId()]
                );
            }
        } else {
            // Encode contacts
            $contacts = $client->getContacts();
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
                'name'          => $client->getName(),
                'taxAddress'    => $client->getTaxAddress(),
                'rfc'           => $client->getRfc(),
                'paymentTerms'  => $client->getPaymentTerms(),
                'creditLimit'   => $client->getCreditLimit(),
                'discount'      => $client->getDiscount(),
                'comments'      => $client->getComments(),
                'contacts'      => $contactsJson,
            ]);
        }

        return new ViewModel([
            'client'      => $client,
            'form'        => $form,
            'formContact' => $formContact,
        ]);
    }
}
