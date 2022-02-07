<?php

namespace Virtue\JWT;

interface VerifiesToken
{
    public function verify(Token $token): void;
}
