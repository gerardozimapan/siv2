<?php
namespace Cotizador\Service\Factory;

use Interop\Container\ContainerInterface;
use Cotizador\Service\MeasureUnitManager;

/**
 * This is the factory class for MeasureUnitManager service. The purpose of the factory
 * is to instantiate the service and pass its despendencies (inject dependencies).
 */
class MeasureUnitManagerFactory
{
    /**
     * This method creates the MeasureUnitManager service and return its instance.
     */
    public function __invoke(ContainerInterface $container, $requestName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new MeasureUnitManager($entityManager);
    }
}
