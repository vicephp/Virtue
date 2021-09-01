<?php

namespace Virtue\JWT;

class ClaimSet
{
    private $claims = [];

    public function issuer(string $iss)
    {
        $this->claims['iss'] = $iss;
    }

    public function subject(string $sub)
    {
        $this->claims['sub'] = $sub;
    }

    public function audience($aud)
    {
        $this->claims['aud'] = $aud;
    }

    public function expirationTime(int $exp)
    {
        $this->claims['exp'] = $exp;
    }

    public function notBefore(int $nbf)
    {
        $this->claims['nbf'] = $nbf;
    }

    public function issuedAt(int $iat)
    {
        $this->claims['iat'] = $iat;
    }

    public function jwtID(string $jti)
    {
        $this->claims['jti'] = $jti;
    }

    public function asArray(): array
    {
        return $this->claims;
    }
}
