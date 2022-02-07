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
        $typ = $token->header('typ');
        if ($typ !== 'JWT') {
            throw new VerificationFailed('Only JWT tokens are allowed');
        }

        $now = time();
        $exp = $token->payload('exp') ?: $now;
        $iat = $token->payload('iat') ?: $now;
        $nbf = $token->payload('nbf') ?: $now;

        $issuer = $token->payload('iss');
        $audience = $token->payload('aud');
        $subject = $token->payload('sub');

        if ($now > $exp) {
            throw new VerificationFailed('Token has expired');
        }

        if (isset($this->settings['iat'])) {
            if (isset($this->settings['iat']['before']) && $iat > $this->settings['iat']['before']) {
                throw new VerificationFailed('Token was issued after expected time');
            }
            if (isset($this->settings['iat']['after']) && $iat < $this->settings['iat']['after']) {
                throw new VerificationFailed('Token was issued before expected time');
            }
        }

        if ($now < $nbf) {
            throw new VerificationFailed('Token is not yet valid');
        }

        if (isset($this->settings['issuers']) && !in_array($issuer, $this->settings['issuers'])) {
            throw new VerificationFailed('Issuer is not allowed');
        }

        if (isset($this->settings['audience']) && $audience !== $this->settings['audience']) {
            throw new VerificationFailed('Audience is not allowed');
        }

        if (isset($this->settings['subjects']) && !in_array($subject, $this->settings['subjects'])) {
            throw new VerificationFailed('Subject is not allowed');
        }

        $alg = $token->header('alg') ?: 'none';
        if (isset($this->settings['algorithms']) && !in_array($alg, $this->settings['algorithms'])) {
            throw new VerificationFailed('Algorithm is not allowed');
        }
    }

}
