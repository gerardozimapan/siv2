<?php
namespace Cotizador\Controller;

use Cotizador\Entity\BankAccount;
use Cotizador\Form\BankAccountForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller is reponsible for currency management (adding, editing
 * and delete bank account).
 */
class BankAccountController extends AbstractActionController
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
     * list of bank accounts.
     */
    public function indexAction()
    {
        $bankAccounts = $this->entityManager->getRepository(BankAccount::class)
            ->findBy([], ['id' => 'ASC']);

        return new ViewModel([
            'bankAccounts' => $bankAccounts,
        ]);
    }
}
