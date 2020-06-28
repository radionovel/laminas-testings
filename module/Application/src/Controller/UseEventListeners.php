<?php

namespace Application\Controller;

use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\Mvc\MvcEvent;

/**
 * Trait UseEventListeners
 *
 * @package Application\Controller
 */
trait UseEventListeners
{

    /**
     * @var array
     */
    private $listeners;

    /**
     * @param MvcEvent $e
     *
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->initEvents();
        return parent::onDispatch($e);
    }

    /**
     * Initialize events listeners
     */
    public function initEvents()
    {
        foreach ($this->listeners as $listener) {
            /**
             * @var ListenerAggregateInterface $listener
             */
            $listener->attach($this->getEventManager());

        }
    }
}
