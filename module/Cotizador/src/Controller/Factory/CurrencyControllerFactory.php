<?php
namespace Cotizador\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Cotizador\Controller\CurrencyController;

/**
 * This is the factory for CurrencyController. It's purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class CurrencyControllerFactory  implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        // Instantiate the controller an inject dependencies.
        return new CurrencyController($entityManager);
    }
}
