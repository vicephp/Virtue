<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWT\Base64Url;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

class ClaimsVerify implements VerifiesToken
{
    private $settings;

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function verify(Token $token): void
    {
        $alg = $token->header('alg');

        $now = time();
        $exp = $token->payload('exp');
        $iat = $token->payload('iat');
        $nbf = $token->payload('nbf');

        $issuer = $token->payload('iss');
        $audience = $token->payload('aud');

        if ($now > $exp) {
            throw new VerificationFailed('Token expired');
        }

        if ($now < $nbf) {
            throw new VerificationFailed('Token not yet valid');
        }

        if (isset($this->settings['issuers']) && !in_array($issuer, $this->settings['issuers'])) {
            throw new VerificationFailed('Issuer not allowed');
        }

        if (isset($this->settings['audience']) && $audience !== $this->settings['audience']) {
            throw new VerificationFailed('Audience not allowed');
        }

        if (isset($this->settings['algorithms']) && !in_array($alg, $this->settings['algorithms'])) {
            throw new VerificationFailed('Algorithm not allowed');
        }
    }

}
