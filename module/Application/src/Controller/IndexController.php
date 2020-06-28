<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application\Controller;

use Application\Listeners\MyEventListener;
use Laminas\EventManager\Event;
use Laminas\Http\PhpEnvironment\Request;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @var MyEventListener
     */
    private $listener;


    /**
     * IndexController constructor.
     */
    public function __construct()
    {



    }

    public function indexAction()
    {

//        $this->getEventManager()->attach('index', function ($event) {
//            var_dump($event);
//        });
//
//        $this->getEventManager()->

        $this->listener = new MyEventListener();
        $this->listener->attach($this->getEventManager());

        $this->getEventManager()->trigger('index', 'new Request()', ['foo' => 'bar']);

        $event = new Event('index');
        $event->setTarget('some object');
        $event->setParams(['bar' => 'baz']);
//
        $this->getEventManager()->triggerEvent($event);

        return new ViewModel();
    }
}
