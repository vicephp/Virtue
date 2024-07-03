<?php

namespace Virtue\JWT;

use Webmozart\Assert\Assert;

/**
 * @phpstan-type Alg = Algorithm::*
 */
abstract class Algorithm
{
    public const RS256 = 'RS256';
    public const RS384 = 'RS384';
    public const RS512 = 'RS512';
    public const HS256 = 'HS256';
    public const HS384 = 'HS384';
    public const HS512 = 'HS512';
    public const None = 'none';


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

    /** @phpstan-assert Alg $name */
    public static function assertIsValid(string $name): void
    {
        Assert::true(defined(sprintf('%s::%s', self::class, $name)), "Algorithm $name is not supported");
    }
}
