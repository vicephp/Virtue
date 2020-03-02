<?php

namespace Virtue\Access\Phalcon\Mvc;

use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Virtue\Access;

class AuthenticationPlugin extends Plugin
{
    public function beforeDispatchLoop(Event $event, Dispatcher $dispatcher) {
        /** @var Access\Identity $user */
        $user = $this->getDI()->get(Access\Identity::class);

        if ($user->isAuthenticated()) {
            return true;
        }

        throw new Access\AuthenticationFailed($user);
    }
}
