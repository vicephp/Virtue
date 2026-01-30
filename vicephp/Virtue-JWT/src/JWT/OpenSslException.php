<?php

namespace Virtue\JWT;

class OpenSslException extends \RuntimeException
{
    /** @var string[] */
    private $errors;

    /**
     * @param string[] $errors
     */
    public function __construct(array $errors, int $code = 0, ?\Throwable $previous = null)
    {
        if (empty($errors)) {
            $errors = ['Unknown OpenSSL error'];
        }
        $this->errors = $errors;
        $message = 'OpenSSL error(s): ' . implode('; ', $errors);
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return string[]
     */
    public static function collectErrors(): array
    {
        $errors = [];
        while ($error = \openssl_error_string()) {
            $errors[] = $error;
        }
        return $errors;
    }
}
