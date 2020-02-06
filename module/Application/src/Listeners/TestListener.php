<?php

namespace Application\Listeners;

use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\MiddlewareController;
use Laminas\Mvc\Exception\InvalidMiddlewareException;
use Laminas\Mvc\MvcEvent;
use Laminas\Psr7Bridge\Psr7Response;
use Laminas\Stdlib\ArrayUtils;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * Class TestListener
 * @package Application\Listeners
 */
class TestListener extends AbstractListenerAggregate
{
    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1000)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], $priority);
    }

    /**
     * @param MvcEvent $event
     * @return mixed|void
     */
    public function onDispatch(MvcEvent $event)
    {
        if (null !== $event->getResult()) {
            return;
        }

        $routeMatch = $event->getRouteMatch();

        $middleware = $routeMatch->getParam('my:middleware', false);
        if (false === $middleware) {
            return;
        }
        /**
         * @var \Laminas\Http\PhpEnvironment\Request $request
         */
        $request        = $event->getRequest();
        $application    = $event->getApplication();
        $response       = $application->getResponse();
        $serviceManager = $application->getServiceManager();

        $middleware = is_array($middleware) ? $middleware : [$middleware];
        foreach ($middleware as $handler) {

            if (is_string($handler) && $serviceManager->has($handler)) {
                $handler = $serviceManager->get($handler);
            }

            if (!is_callable($handler) &&  ! $handler instanceof \stdClass) {
                throw new \RuntimeException('My middleware error');
            }

            if (is_callable($handler)) {
                $result = $handler($request, $response);
            } else {
                $result = $handler->handle($request, $response);
            }

            if ($result instanceof Response) {
                $event->setResult($result);
                return $result;
            }
            return $event->getResult();
        }


        $query = $request->getQuery();
        $query['asdfsdf'] = 'dddd';
        $request->setQuery($query);

        return $event->getResult();


//        $psr7ResponsePrototype = Psr7Response::fromLaminas($response);


        /*


        try {
            $pipe = $this->createPipeFromSpec(
                $serviceManager,
                $psr7ResponsePrototype,
                is_array($middleware) ? $middleware : [$middleware]
            );
        } catch (InvalidMiddlewareException $invalidMiddlewareException) {
            $return = $this->marshalInvalidMiddleware(
                $application::ERROR_MIDDLEWARE_CANNOT_DISPATCH,
                $invalidMiddlewareException->toMiddlewareName(),
                $event,
                $application,
                $invalidMiddlewareException
            );
            $event->setResult($return);
            return $return;
        }

        $caughtException = null;
        try {
            $return = (new MiddlewareController(
                $pipe,
                $psr7ResponsePrototype,
                $application->getServiceManager()->get('EventManager'),
                $event
            ))->dispatch($request, $response);
        } catch (\Throwable $ex) {
            $caughtException = $ex;
        } catch (\Exception $ex) {  // @TODO clean up once PHP 7 requirement is enforced
            $caughtException = $ex;
        }

        if ($caughtException !== null) {
            $event->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $event->setError($application::ERROR_EXCEPTION);
            $event->setParam('exception', $caughtException);

            $events  = $application->getEventManager();
            $results = $events->triggerEvent($event);
            $return  = $results->last();
            if (! $return) {
                $return = $event->getResult();
            }
        }

        $event->setError('');

        if (! $return instanceof PsrResponseInterface) {
            $event->setResult($return);
            return $return;
        }
        $response = Psr7Response::toLaminas($return);
        $event->setResult($response);
        return $response;
        */
    }
}
