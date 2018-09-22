<?php
namespace Cotizador\Service\Factory;

use Cotizador\Service\ComponentManager;
use Interop\Container\ContainerInterface;

/**
 * This is the factory class for ComponentManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ComponentManagerFactory
{
    /**
     * This method creates the ComponentManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requestName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new ComponentManager($entityManager);
    }
}