<?php

declare(strict_types=1);

namespace Application\Listeners;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Logger\Interfaces\Logger;

/**
 * Class LoggerListenerFactory
 *
 * @package Application\Listeners
 */
class LoggerListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new LoggerListener(
            $container->get(Logger::class)
        );
    }
}
