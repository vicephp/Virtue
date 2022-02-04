<?php

namespace Virtue\JWT;

class Token
{
    private $headers = [
        'alg' => 'none',
        'typ' => 'JWT'
    ];
    private $payload = [];
    private $signature = '';

    public function __construct(array $headers, array $payload)
    {
        $this->headers = array_replace($this->headers, $headers);
        $this->payload = $payload;
    }

    public static function ofString(string $token): Token
    {
        list($header, $payload, $signature) = explode('.', $token);
        $header = json_decode(Base64Url::decode($header), true);
        $payload = json_decode(Base64Url::decode($payload), true);

        $token = new self($header, $payload);
        $token->signature = Base64Url::decode($signature);

        return $token;
    }

    public function header($name)
    {
        return $this->headers[$name] ?? '';
    }

    public function payload($name)
    {
        return $this->payload[$name] ?? '';
    }

    public function signature(): string
    {
        return $this->signature;
    }

    public function withoutSig(): string
    {
        return Base64Url::encode(json_encode($this->headers)) . '.' . Base64Url::encode(json_encode($this->payload));
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
