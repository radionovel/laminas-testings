<?php

namespace Application\Events;

use Laminas\EventManager\Event;

abstract class BaseEvent extends Event
{
    public function __construct($target = null, $params = null)
    {
        parent::__construct(get_class($this), $target, $params);
    }
}