<?php
namespace Cotizador\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cotizador\Controller\BrandController;
use Cotizador\Service\BrandManager;

/**
 * This is the factory for BrandController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class BrandControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $brandManager  = $container->get(BrandManager::class);

        // Instantiate the controller an inject dependencies
        return new BrandController($entityManager, $brandManager);
    }
}
