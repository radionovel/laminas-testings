<?php

/**
 * @see       https://github.com/laminas/laminas-mvc for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc/blob/master/LICENSE.md New BSD License
 */

namespace Application\Listeners;

use ArrayObject;
use Interop\Http\Server\MiddlewareInterface;
use Laminas\EventManager\AbstractListenerAggregate;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\Exception\InvalidMiddlewareException;
use Laminas\Mvc\MvcEvent;
use Laminas\Router\RouteMatch;
use Laminas\Stdlib\ArrayUtils;


/**
 * Default dispatch listener
 *
 * Pulls controllers from the service manager's "ControllerManager" service.
 *
 * If the controller cannot be found a "404" result is set up. Otherwise it
 * will continue to try to load the controller.
 *
 * If the controller is not dispatchable it sets up a "404" result. In case
 * of any other exceptions it trigger the "dispatch.error" event in an attempt
 * to return a 500 status.
 *
 * If the controller subscribes to InjectApplicationEventInterface, it injects
 * the current MvcEvent into the controller.
 *
 * It then calls the controller's "dispatch" method, passing it the request and
 * response. If an exception occurs, it triggers the "dispatch.error" event,
 * in an attempt to return a 500 status.
 *
 * The return value of dispatching the controller is placed into the result
 * property of the MvcEvent, and returned.
 */
class TestListener extends AbstractListenerAggregate
{
    /**
     * @var Controller\ControllerManager
     */
    private $controllerManager;

    public function __construct ()
    {
//        $this->controllerManager = $controllerManager;
    }

    /**
     * Attach listeners to an event manager
     *
     * @param EventManagerInterface $events
     * @param int                   $priority
     *
     * @return void
     */
    public function attach (EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 1000);
    }

    /**
     * Listen to the "dispatch" event
     *
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function onDispatch (MvcEvent $e)
    {
        if (null !== $e->getResult()) {
            return;
        }

        $routeMatch = $e->getRouteMatch();
        $middlewares = $routeMatch instanceof RouteMatch
            ? $routeMatch->getParam('my:middleware', false)
            : false;
        $application = $e->getApplication();
        $events = $application->getEventManager();
        $serviceManager = $application->getServiceManager(); // LOCATOR

        if ($middlewares === false) {
            return $e->getResult();
        } else if (is_string($middlewares)) {
            $middlewares = [$middlewares];
        } else if (! is_array($middlewares)) {
            return $e->getResult();
        }

        $pipe = [];

        $request = $e->getRequest();
        $query = $request->getQuery();
        $query->set('te', 'st');
        $request->setQuery($query);

        $return = $e->getResult();
        return $return;

//
//        foreach ($middlewares as $middleware) {
//            if (null === $middleware) {
//                throw InvalidMiddlewareException::fromNull();
//            }
//
//            $middlewareName = is_string($middleware) ? $middleware : get_class($middleware);
//
//            if (is_string($middleware) && $serviceManager->has($middleware)) {
//                $middleware = $serviceManager->get($middleware);
//            }
//            if (! $middleware instanceof MiddlewareInterface && ! is_callable($middleware)) {
//                throw InvalidMiddlewareException::fromMiddlewareName($middlewareName);
//            }
//
//            $pipe[] = $middleware;
//        }
//
//        foreach ($pipe as $middleware) {
//            $response = $middleware();
//        }


        /*
        $controllerManager = $this->controllerManager;


        // Query abstract controllers, too!
        if (! $controllerManager->has($controllerName)) {
            $return = $this->marshalControllerNotFoundEvent(
                $application::ERROR_CONTROLLER_NOT_FOUND,
                $controllerName,
                $e,
                $application
            );
            return $this->complete($return, $e);
        }

        try {
            $controller = $controllerManager->get($controllerName);
        } catch (Exception\InvalidControllerException $exception) {
            $return = $this->marshalControllerNotFoundEvent(
                $application::ERROR_CONTROLLER_INVALID,
                $controllerName,
                $e,
                $application,
                $exception
            );
            return $this->complete($return, $e);
        } catch (InvalidServiceException $exception) {
            $return = $this->marshalControllerNotFoundEvent(
                $application::ERROR_CONTROLLER_INVALID,
                $controllerName,
                $e,
                $application,
                $exception
            );
            return $this->complete($return, $e);
        } catch (\Throwable $exception) {
            $return = $this->marshalBadControllerEvent($controllerName, $e, $application, $exception);
            return $this->complete($return, $e);
        } catch (\Exception $exception) {  // @TODO clean up once PHP 7 requirement is enforced
            $return = $this->marshalBadControllerEvent($controllerName, $e, $application, $exception);
            return $this->complete($return, $e);
        }

        if ($controller instanceof InjectApplicationEventInterface) {
            $controller->setEvent($e);
        }

        $request  = $e->getRequest();
        $response = $application->getResponse();
        $caughtException = null;

        try {
            $return = $controller->dispatch($request, $response);
        } catch (\Throwable $ex) {
            $caughtException = $ex;
        } catch (\Exception $ex) {  // @TODO clean up once PHP 7 requirement is enforced
            $caughtException = $ex;
        }

        if ($caughtException !== null) {
            $e->setName(MvcEvent::EVENT_DISPATCH_ERROR);
            $e->setError($application::ERROR_EXCEPTION);
            $e->setController($controllerName);
            $e->setControllerClass(get_class($controller));
            $e->setParam('exception', $caughtException);

            $return = $application->getEventManager()->triggerEvent($e)->last();
            if (! $return) {
                $return = $e->getResult();
            }
        }

        return $this->complete($return, $e);
        */
    }

    /**
     * Complete the dispatch
     *
     * @param mixed    $return
     * @param MvcEvent $event
     *
     * @return mixed
     */
    protected function complete ($return, MvcEvent $event)
    {
        if (! is_object($return)) {
            if (ArrayUtils::hasStringKeys($return)) {
                $return = new ArrayObject($return, ArrayObject::ARRAY_AS_PROPS);
            }
        }
        $event->setResult($return);
        return $return;
    }
}
