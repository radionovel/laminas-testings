<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application;

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Http\Headers;
use Laminas\Json\Json;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\JsonModel;

class Module
{
    public function getConfig(): array
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $application = $event->getApplication();
        $event_manager = $application->getEventManager();
        $event_manager->attach('dispatch', function (MvcEvent $event) {
            $application = $event->getApplication();
            $service_manager = $application->getServiceManager();
            $controllers = $service_manager->get('config')['auth:require'];

            $router_match = $event->getRouteMatch();
            $controller = $router_match->getParam('controller');

            if (in_array($controller, $controllers)) {
                $request = $event->getRequest();
                $query = $request->getQuery();
                $query->set('query-tset', 'my-value');
                $request->setQuery($query);
            }

            $response = new \Laminas\Http\Response;
            $response->setStatusCode(200);
            $headers = new Headers();
            $headers->addHeaderLine('Content-Type', 'application/json');
            $response->setHeaders($headers);
            $response->setContent(
                Json::encode(['source' => 'Module'])
            );

//            return $response;
        }, 10);
    }
}
