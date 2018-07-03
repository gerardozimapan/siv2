<?php
namespace Cotizador\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cotizador\Controller\MeasureUnitController;
use Cotizador\Service\MeasureUnitManager;

/**
 * This is the factory for MeasureUnitController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class MeasureUnitControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $measureUnitManager  = $container->get(MeasureUnitManager::class);

        // Instantiate the controller an inject dependencies
        return new MeasureUnitController($entityManager, $measureUnitManager);
    }
}
