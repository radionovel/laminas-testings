<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Application\Controller;

use Application\Events\IndexEvent;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    use UseEventListeners;

    /**
     * IndexController constructor.
     *
     * @param array $listeners
     */
    public function __construct($listeners = [])
    {
        $this->listeners = $listeners;
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $event = new IndexEvent('some object');
        $this->getEventManager()->triggerEvent($event);
        return new ViewModel();
    }
}
