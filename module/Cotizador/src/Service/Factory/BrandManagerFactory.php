<?php
namespace Cotizador\Service\Factory;

use Interop\Container\ContainerInterface;
use Cotizador\Service\BrandManager;

/**
 * This is the factory class for BrandManager service. The purpose of the factory
 * is to instantiate the service and pass its despendencies (inject dependencies).
 */
class BrandManagerFactory
{
    /**
     * This method creates the BrandManager service and return its instance.
     */
    public function __invoke(ContainerInterface $container, $requestName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new BrandManager($entityManager);
    }
}
