<?php

namespace Virtue\JWT\Algorithms;

use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;
use Virtue\JWT\VerifiesToken;

/**
 * @phpstan-type ClaimsVerifySettings array{
 *   required?: string[],
 *   issuers?: string[],
 *   audience?: string,
 *   subjects?: string[],
 *   leeway?: int,
 *   iat?: array{before?: int, after?: int},
 *   algorithms?: string[]
 * }
 */
class ClaimsVerify implements VerifiesToken
{
    /** @var ClaimsVerifySettings */
    private $settings;

    /** @param ClaimsVerifySettings $settings */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function verify(Token $token): void
    {
        $typ = $token->headers('typ');
        if ($typ !== 'JWT') {
            throw new VerificationFailed('Only JWT tokens are allowed');
        }

        foreach ($this->settings['required'] ?? [] as $claim) {
            if (!$token->payload($claim)) {
                throw new VerificationFailed("Required claim '$claim' is missing");
            }
        }

        $now = time();
        $exp = $token->payload('exp') ?: $now;
        $iat = $token->payload('iat') ?: $now;
        $nbf = $token->payload('nbf') ?: $now;

        $leeway = $this->settings['leeway'] ?? 0;

        $issuer = $token->payload('iss');
        $audience = $token->payload('aud');
        $audience = is_string($audience) ? [$audience] : (is_array($audience) ? $audience : []);
        $subject = $token->payload('sub');

        if ($now > $exp + $leeway) {
            throw new VerificationFailed('Token has expired');
        }

        if (isset($this->settings['iat'])) {
            if (isset($this->settings['iat']['before']) && $iat > $this->settings['iat']['before'] + $leeway) {
                throw new VerificationFailed('Token was issued after expected time');
            }
            if (isset($this->settings['iat']['after']) && $iat < $this->settings['iat']['after'] - $leeway) {
                throw new VerificationFailed('Token was issued before expected time');
            }
        }

        if ($now < $nbf - $leeway) {
            throw new VerificationFailed('Token is not yet valid');
        }

        if (isset($this->settings['issuers']) && !in_array($issuer, $this->settings['issuers'])) {
            throw new VerificationFailed('Issuer is not allowed');
        }

        if (isset($this->settings['audience']) && !in_array($this->settings['audience'], $audience)) {
            throw new VerificationFailed('Audience is not allowed');
        }

        if (isset($this->settings['subjects']) && !in_array($subject, $this->settings['subjects'])) {
            throw new VerificationFailed('Subject is not allowed');
        }

        $alg = $token->headers('alg', 'none');
        if (isset($this->settings['algorithms']) && !in_array($alg, $this->settings['algorithms'])) {
            throw new VerificationFailed('Algorithm is not allowed');
        }
    }
}
