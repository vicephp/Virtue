<?php

namespace Virtue\Access\Authenticators;

use Virtue\Access;

class AlwaysGuest implements Access\Authenticates
{
    /** @var Access\Identity */
    private $user;

    public function authenticate(Access\Login $login): Access\Identity
    {
        isset($this->user) || $this->user = new Access\Identities\Guest();

        return $this->user;
    }
}

