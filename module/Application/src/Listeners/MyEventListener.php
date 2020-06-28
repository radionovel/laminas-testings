<?php

namespace Application\Listeners;

use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;

class MyEventListener implements ListenerAggregateInterface
{

    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach('index', [$this, 'index'], $priority);
    }

    public function index(EventInterface $event)
    {
        echo 'from listener';
        var_dump($event);
    }
}