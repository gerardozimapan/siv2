<?php
namespace Cotizador\Service\Factory;

use Cotizador\Service\ImageManager;
use Interop\Container\ContainerInterface;

/**
 * This is the factory class for ImageManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class ImageManagerFactory
{
    /**
     * This method create the ImageManager service and return its instance.
     */
    public function __invoke(ContainerInterface $container, $requestName, array $options = null)
    {
        return new ImageManager();
    }
}