<?php
declare(strict_types=1);

namespace Logger;

use Logger\Interfaces\Logger;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Session\ConfigProvider;

class Module
{
    /**
     * Retrieve default laminas-session config for laminas-mvc context.
     *
     * @return array
     */
    public function getConfig()
    {
        $provider = new ConfigProvider();
        $config = [
            'service_manager' => $provider->getDependencyConfig(),
        ];

        $config['service_manager']['factories'][Logger::class] = LoggerFactory::class;

//        $config['service_manager']['logger']['driver'];
        return $config;

//        Logger::class => LoggerFactory::class
    }

    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $foo = 'bar';
    }

}