<?php
namespace Cotizador\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cotizador\Controller\ClientController;
use Cotizador\Service\ClientManager;

/**
 * This is the facotory for ClientController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ClientControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $clientManager = $container->get(ClientManager::class);

        // Instantiate the controller an inject dependencies
        return new ClientController($entityManager, $clientManager);
    }
}
