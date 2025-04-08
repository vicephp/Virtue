<?php

namespace Virtue\JWT;

/**
 * @phpstan-type Alg = 'RS256'|'RS384'|'RS512'|'HS256'|'HS384'|'HS512'|string
 */
abstract class Algorithm
{
    /** @var Alg */
    protected $name;

    /** @param Alg $name */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /** @return Alg */
    public function __toString()
    {
        return $this->name;
    }
}
