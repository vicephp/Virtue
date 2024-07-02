<?php

namespace Virtue\JWT;

/**
 * @phpstan-type Claims = array{
 *   iss?: string,
 *   sub?: string,
 *   aud?: string|string[],
 *   exp?: int,
 *   nbf?: int,
 *   iat?: int,
 *   ...
 * }
 */
class ClaimSet
{
    /** @var Claims */
    private $claims = [];

    public function issuer(string $iss): void
    {
        $this->claims['iss'] = $iss;
    }

    public function subject(string $sub): void
    {
        $this->claims['sub'] = $sub;
    }

    /** @param string[]|string $aud */
    public function audience($aud): void
    {
        $this->claims['aud'] = $aud;
    }

    public function expirationTime(int $exp): void
    {
        $this->claims['exp'] = $exp;
    }

    public function notBefore(int $nbf): void
    {
        $this->claims['nbf'] = $nbf;
    }

    public function issuedAt(int $iat): void
    {
        $this->claims['iat'] = $iat;
    }

    public function jwtID(string $jti): void
    {
        $this->claims['jti'] = $jti;
    }

    /**
     * @return Claims
     */
    public function asArray(): array
    {
        return $this->claims;
    }
}
