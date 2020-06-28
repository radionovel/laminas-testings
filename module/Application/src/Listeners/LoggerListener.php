<?php

namespace Application\Listeners;

use Application\Events\IndexEvent;
use Logger\Interfaces\Logger;
use Laminas\EventManager\EventInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use Laminas\EventManager\ListenerAggregateTrait;

class LoggerListener implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * LoggerListener constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(IndexEvent::class, [$this, 'index'], $priority);
    }

    public function index(EventInterface $event)
    {
        $this->logger->log('from listener');
        var_dump($event);
    }
}