<?php

namespace Virtue\Access\Phalcon\Mvc;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\User\Plugin;
use Virtue\Access;

class AuthorizationPlugin extends Plugin
{
    const RouteAccess = 'virtue.access.routeAccess';
    const ControllerAccess = 'virtue.access.controllerAccess';

    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher)
    {
        /** @var Router $router */
        $router = $this->getDI()->get('router');
        /** @var Access\GrantsAccess $routeAccess */
        $routeAccess = $this->getDI()->get(self::RouteAccess);
        $resource = $router->wasMatched() ? $router->getMatchedRoute()->getPattern() : '';
        if ($routeAccess->granted($resource)) {
            return true;
        }
        /** @var Access\Identity $user */
        $user = $this->getDI()->get(Access\Identity::class);

        throw new Access\AuthorizationFailed($user, $resource);
    }

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        /** @var Router $router */
        $router = $dispatcher->getDi()->get('router');
        /** @var Access\GrantsAccess $controllerAccess */
        $controllerAccess = $dispatcher->getDI()->get(self::ControllerAccess);
        $resource = "{$router->getControllerName()}:{$router->getActionName()}";
        if ($controllerAccess->granted($resource)) {
            return true;
        }
        /** @var Access\Identity $user */
        $user = $this->getDI()->get(Access\Identity::class);

        throw new Access\AuthorizationFailed($user, $resource);
    }
}
