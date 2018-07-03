<?php
namespace Cotizador\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cotizador\Controller\SupplierController;
use Cotizador\Service\SupplierManager;

/**
 * This is the facotory for SupplierController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class SupplierControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $supplierManager = $container->get(SupplierManager::class);

        // Instantiate the controller an inject dependencies
        return new SupplierController($entityManager, $supplierManager);
    }
}
