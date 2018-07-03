<?php
namespace Cotizador\Service\Factory;

use Interop\Container\ContainerInterface;
use Cotizador\Service\SupplierManager;

/**
 * This is the facotry class for SupplierManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class SupplierManagerFactory
{
    /**
     * This method creates the SupplierManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new SupplierManager($entityManager);
    }
}
