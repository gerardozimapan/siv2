<?php
namespace Cotizador\Service\Factory;

use Interop\Container\ContainerInterface;
use Cotizador\Service\CategoryManager;

/**
 * This is the factory class for CategoryManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class CategoryManagerFactory
{
    /**
     * This method creates the CategoryManager service and returns its instance.
     */
    public function __invoke(ContainerInterface $container, $requesName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        return new CategoryManager($entityManager);
    }
}
