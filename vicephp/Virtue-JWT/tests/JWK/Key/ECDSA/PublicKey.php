<?php

namespace Virtue\JWK\Key\ECDSA;

use Virtue\JWK\AsymmetricKey;

class PublicKey implements AsymmetricKey
{
    /** @var string */
    private $alg;

    /** @var string */
    private $pem;

    private function __construct()
    {
    }

    public static function fromPem(string $pem): self
    {
        $key = new self();
        $key->pem = $pem;
        $public = \openssl_pkey_get_public($pem);
        $details = openssl_pkey_get_details($public);
        //TODO remove together with the support of PHP versions < 8.0
        if (version_compare(PHP_VERSION, '8.0.0') < 0) {
            \openssl_pkey_free($public);
        }

        if ($details['type'] !== OPENSSL_KEYTYPE_EC) {
            throw new \InvalidArgumentException('Key is not ECDSA');
        }

        $key->alg = 'ES' . $details['bits'];

        return $key;
    }
    /**
     * @inheritDoc
     */
    public function alg(): string
    {
        return $this->alg;
    }

    /**
     * @inheritDoc
     */
    public function asPem(): string
    {
        return $this->pem;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
    }
}
