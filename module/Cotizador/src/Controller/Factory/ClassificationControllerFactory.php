<?php
namespace Cotizador\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cotizador\Controller\ClassificationController;
use Cotizador\Service\ClassificationManager;

/**
 * This is the factory for ClassificationController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class ClassificationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $classificationManager  = $container->get(ClassificationManager::class);

        // Instantiate the controller an inject dependencies
        return new ClassificationController($entityManager, $classificationManager);
    }
}
