<?php
namespace Cotizador\Service\Factory;

use Cotizador\Service\DatasheetManager;
use Interop\Container\ContainerInterface;

/**
 * This is the factory class for DatasheetManager service. The purpose of the factory
 * is to instantiate the service and pass it dependecies (inject dependencies).
 */
class DatasheetManagerFactory
{
    /**
     * This method create the DatasheetManager service and return its instance.
     */
    public function __invoke(ContainerInterface $container, $requestName, array $options = null)
    {
        return new DatasheetManager();
    }
}