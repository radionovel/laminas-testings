<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Listeners\LoggerListener;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Class IndexControllerFactory
 *
 * @package Application\Controller
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $listeners = [];
        $listeners[] = $container->get(LoggerListener::class);
        return new IndexController($listeners);
    }
}
