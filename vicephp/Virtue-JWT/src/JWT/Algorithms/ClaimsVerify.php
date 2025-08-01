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
            throw new VerificationFailed('Only JWT tokens are allowed', VerificationFailed::INVALID_TOKEN);
        }

        foreach ($this->settings['required'] ?? [] as $claim) {
            if (!$token->payload($claim)) {
                throw new VerificationFailed("Required claim '{$claim}' is missing", VerificationFailed::INVALID_TOKEN);
            }
        }

        $now = time();
        /** @var int $exp */
        $exp = $token->payload('exp') ?: $now;
        /** @var int $iat */
        $iat = $token->payload('iat') ?: $now;
        /** @var int $nbf */
        $nbf = $token->payload('nbf') ?: $now;
        $leeway = $this->settings['leeway'] ?? 0;

        if ($now > $exp + $leeway) {
            throw new VerificationFailed('Token has expired', VerificationFailed::INVALID_TOKEN);
        }

        if (isset($this->settings['iat'])) {
            if (isset($this->settings['iat']['before']) && $iat > $this->settings['iat']['before'] + $leeway) {
                throw new VerificationFailed('Token was issued after expected time', VerificationFailed::INVALID_TOKEN);
            }
            if (isset($this->settings['iat']['after']) && $iat < $this->settings['iat']['after'] - $leeway) {
                throw new VerificationFailed('Token was issued before expected time', VerificationFailed::INVALID_TOKEN);
            }
        }

        if ($now < $nbf - $leeway) {
            throw new VerificationFailed('Token is not yet valid', VerificationFailed::INVALID_TOKEN);
        }

        /** @var string $issuer */
        $issuer = $token->payload('iss');
        if (isset($this->settings['issuers']) && !in_array($issuer, $this->settings['issuers'], true)) {
            throw new VerificationFailed("Issuer '{$issuer}' is not allowed", VerificationFailed::INVALID_ISSUER);
        }

        $audience = $token->payload('aud');
        $audience = is_string($audience) ? [$audience] : (is_array($audience) ? $audience : []);
        if (isset($this->settings['audience']) && !in_array($this->settings['audience'], $audience, true)) {
            throw new VerificationFailed(
                sprintf("Audience '%s' is not allowed", implode(', ', $audience)),
                VerificationFailed::INVALID_AUDIENCE
            );
        }

        /** @var string $subject */
        $subject = $token->payload('sub');
        if (isset($this->settings['subjects']) && !in_array($subject, $this->settings['subjects'], true)) {
            throw new VerificationFailed("Subject '{$subject}' is not allowed", VerificationFailed::INVALID_SUBJECT);
        }

        /** @var string $alg */
        $alg = $token->headers('alg', 'none');
        if (isset($this->settings['algorithms']) && !in_array($alg, $this->settings['algorithms'], true)) {
            throw new VerificationFailed("Algorithm '{$alg}' is not allowed", VerificationFailed::INVALID_ALGORITHM);
        }
    }
}
