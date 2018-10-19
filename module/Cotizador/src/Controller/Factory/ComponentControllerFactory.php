<?php
namespace Cotizador\Controller\Factory;

use Cotizador\Controller\ComponentController;
use Cotizador\Service\ComponentManager;
use Cotizador\Service\ImageManager;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for ComponentController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ComponentControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager    = $container->get('doctrine.entitymanager.orm_default');
        $componentManager = $container->get(ComponentManager::class);
        $imageManager     = $container->get(ImageManager::class);

        // Instantiate the controller an inject dependencies
        return new ComponentController($entityManager, $componentManager, $imageManager);
    }
}