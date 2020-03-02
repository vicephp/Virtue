<?php

namespace Virtue\Access;

interface Authenticates
{
    public function authenticate(Login $login): Identity;
}
