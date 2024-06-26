<?php

namespace Virtue\JWT\Algorithms;

use PHPUnit\Framework\TestCase;
use Virtue\JWK\Key\ECDSA;
use Virtue\JWT\Token;
use Virtue\JWT\VerificationFailed;

class ECDSATest extends TestCase
{
    private const PRIVATE_KEY = "-----BEGIN PRIVATE KEY-----\n" .
        "MIGHAgEAMBMGByqGSM49AgEGCCqGSM49AwEHBG0wawIBAQQgevZzL1gdAFr88hb2\n" .
        "OF/2NxApJCzGCEDdfSp6VQO30hyhRANCAAQRWz+jn65BtOMvdyHKcvjBeBSDZH2r\n" .
        "1RTwjmYSi9R/zpBnuQ4EiMnCqfMPWiZqB4QdbAd0E7oH50VpuZ1P087G\n" .
        "-----END PRIVATE KEY-----";

    private const PUBLIC_KEY = "-----BEGIN PUBLIC KEY-----\n" .
        "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEEVs/o5+uQbTjL3chynL4wXgUg2R9\n" .
        "q9UU8I5mEovUf86QZ7kOBIjJwqnzD1omageEHWwHdBO6B+dFabmdT9POxg==\n" .
        "-----END PUBLIC KEY-----";

    public function testVerify()
    {
        $token = Token::ofString("eyJhbGciOiJFUzI1NiIsInR5cCI6IkpXVCJ9.eyJmb28iOiJiYXIifQ.JQ59_MfvAyMcbIIwfwGtNx05VT_Cd7TU11KiqMZ7SkwhVuz-zrzgwEreiTxilY6FuVfW3bi-qP6ai64pud04ZA");
        $ecdsaVerify = new ECDSAVerify(ECDSA\PublicKey::fromPem(self::PUBLIC_KEY));
        $ecdsaVerify->verify($token);
        $this->addToAssertionCount(1);
    }

    public function testWrongSignature()
    {
        echo self::PRIVATE_KEY;
        echo "\n";
        echo self::PUBLIC_KEY;
        $token = Token::ofString("eyJhbGciOiJFUzI1NiIsInR5cCI6IkpXVCJ9.eyJmb28iOiJiYXIifQ.daxHOLL2WBVEHejBUIKNNi8rJ_y-5TnCyZw_vHKy1fRafFkX1JLvZs7N0zEr4WQJ0K7jiXbiBFZ_ogRiMBAbnw");
        var_dump($token->signature());
        $ecdsaVerify = new ECDSAVerify(ECDSA\PublicKey::fromPem(self::PUBLIC_KEY));
        $this->expectException(VerificationFailed::class);
        $ecdsaVerify->verify($token);
    }
}
