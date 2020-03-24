<?php

namespace Virtue\Access;

use Assert\Assertion as Assert;

class Login
{
    const Username = 'username';
    const Password = 'password';

    private $params = [
        self::Username => '',
        self::Password => '',
    ];

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->assertParamsAreValid($params);
        $this->params = array_replace_recursive($this->params, $params);
    }

    private function assertParamsAreValid(array $params)
    {
        $required = [self::Username, self::Password];
        foreach ($required as $key) {
            Assert::keyIsset($params, $key);
        }
    }

    public function getUsername()
    {
        return $this->params[self::Username];
    }

    public function asArray()
    {
        return $this->params;
    }
}