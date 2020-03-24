<?php

namespace Virtue\Access\Authenticators;

use Virtue\Access;

class AlwaysRoot implements Access\Authenticates
{
    /** @var Access\Identity */
    private $user;

    public function authenticate(Access\Login $login): Access\Identity
    {
        $this->user = $this->user ?? new Access\Identities\Root();

        return $this->user;
    }
}
