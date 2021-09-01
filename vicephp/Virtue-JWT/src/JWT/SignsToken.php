<?php

namespace Virtue\JWT;

interface SignsToken
{
    public function sign(string $msg): string;
    public function __toString();
}
