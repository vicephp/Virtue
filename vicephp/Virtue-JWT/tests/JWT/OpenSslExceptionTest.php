<?php

namespace Virtue\JWT;

use PHPUnit\Framework\TestCase;

class OpenSslExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Drain the OpenSSL error queue before each test
        while (\openssl_error_string() !== false) {
            // Intentionally empty
        }
    }

    public function testCollectErrorsWithNoErrors(): void
    {
        $exception = OpenSslException::collectErrors();

        $this->assertEquals(['Unknown OpenSSL error'], $exception->getErrors());
        $this->assertEquals('OpenSSL error(s): Unknown OpenSSL error', $exception->getMessage());
    }

    public function testCollectErrorsWithMultipleErrors(): void
    {
        @\openssl_pkey_get_private('invalid-key-data');

        $exception = OpenSslException::collectErrors();

        $errors = $exception->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertIsArray($errors);

        $expectedMessage = 'OpenSSL error(s): ' . implode('; ', $errors);
        $this->assertEquals($expectedMessage, $exception->getMessage());
    }

    public function testGetErrors(): void
    {
        @\openssl_pkey_get_private('invalid-key');

        $exception = OpenSslException::collectErrors();
        $errors = $exception->getErrors();

        $this->assertIsArray($errors);
        $this->assertNotEmpty($errors);
        foreach ($errors as $error) {
            $this->assertIsString($error);
        }
    }

    public function testExceptionCanBeUsedAsPrevious(): void
    {
        $opensslException = OpenSslException::collectErrors();
        $wrappedException = new \RuntimeException('Wrapper exception', 0, $opensslException);

        $this->assertSame($opensslException, $wrappedException->getPrevious());
        $this->assertEquals('OpenSSL error(s): Unknown OpenSSL error', $opensslException->getMessage());
    }
}
