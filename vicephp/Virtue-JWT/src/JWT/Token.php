<?php

namespace Virtue\JWT;

use Webmozart\Assert\Assert;

/**
 * @phpstan-import-type Alg from Algorithm
 * @phpstan-import-type Claims from ClaimSet
 * @phpstan-type TokenType = 'JWT'
 * @phpstan-type TokenHeader = array{alg: Alg, typ: TokenType, ...}
 */
class Token
{
    /** @var TokenHeader&array<string,mixed> */
    private $headers = [
        'alg' => 'none',
        'typ' => 'JWT'
    ];
    /** @var array<string,mixed> */
    private $payload = [];
    /** @var string */
    private $signature = '';
    /** @var string */
    private $msg = '';

    /**
     * @param array<string,mixed> $headers
     * @param array<string,mixed> $payload
     */
    public function __construct(array $headers, array $payload)
    {
        /** @var TokenHeader&array<string,mixed> $tokenHeaders */
        $tokenHeaders = array_replace($this->headers, $headers);
        $this->headers = $tokenHeaders;
        $this->payload = $payload;
    }

    public static function ofString(string $token): Token
    {
        $token = explode('.', $token);
        count($token) != 3 && $token = ['', '', ''];
        [$header, $payload, $signature] = $token;
        $msg = $header . '.' . $payload;

        $header = json_decode(Base64Url::decode($header), true);
        $payload = json_decode(Base64Url::decode($payload), true);
        $signature = Base64Url::decode($signature);

        if (!is_array($header) || !is_array($payload) || !$signature) {
            $header = ['alg' => 'malformed', 'typ' => 'malformed'];
            $payload = [];
            $signature = '';
        }

        Assert::isMap($header);
        Assert::isMap($payload);
        $token = new self($header, $payload);
        $token->signature = $signature;

        // Preserve the original token string, since json_encode can change the order of the keys
        // and thus the payload will be different from the original.
        $token->msg = $msg;

        return $token;
    }

    /**
     * @deprecated please use headers()
     * @return mixed
     */
    public function header(string $name)
    {
        return $this->headers($name);
    }

    /**
     * @param mixed $default
     * @return ($name is '' ? TokenHeader : mixed)
     */
    public function headers(string $name = '', $default = null)
    {
        return $name ? $this->headers[$name] ?? $default : $this->headers;
    }

    /**
     * @param mixed $default
     * @return ($name is '' ? Claims & array<string,mixed> : mixed)
     */
    public function payload(string $name = '', $default = null)
    {
        return $name ? $this->payload[$name] ?? $default : $this->payload;
    }

    public function signature(): string
    {
        return $this->signature;
    }

    public function withoutSig(): string
    {
        return $this->msg ?:
            $this->msg = Base64Url::encode(json_encode($this->headers, JSON_THROW_ON_ERROR)) . '.' .
                Base64Url::encode(json_encode($this->payload, JSON_THROW_ON_ERROR));
    }

    public function signWith(SignsToken $alg): Token
    {
        $token = new Token(
            array_replace($this->headers, ['alg' => (string) $alg]),
            $this->payload
        );
        $token->signature = $alg->sign($token->withoutSig());

        return $token;
    }

    public function verifyWith(VerifiesToken $alg): void
    {
        $alg->verify($this);
    }

    public function __toString(): string
    {
        return $this->withoutSig() . '.' . Base64Url::encode($this->signature);
    }
}
