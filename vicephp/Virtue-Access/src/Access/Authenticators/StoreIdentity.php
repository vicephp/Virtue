<?php

namespace Virtue\Access\Authenticators;

use Virtue\Access\Authenticates;
use Virtue\Access\Identity;
use Virtue\Access\Login;
use Virtue\Http\Session\StoresVariables;

class StoreIdentity implements Authenticates
{
    /** @var Authenticates */
    private $authority;
    /** @var StoresVariables */
    private $session;

    public function __construct(Authenticates $authority, StoresVariables $session)
    {
        $this->authority = $authority;
        $this->session = $session;
    }

    public function authenticate(Login $login): Identity
    {
        $user = $this->authority->authenticate($login);
        $this->session->set(Identity::class, $user);

        return $user;
    }
}
