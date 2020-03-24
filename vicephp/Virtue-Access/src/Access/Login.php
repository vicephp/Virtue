<?php

namespace Virtue\Access;

use Webmozart\Assert\Assert;

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
        Assert::string($params[self::Username] ?? null);
        Assert::string($params[self::Password] ?? null);
        $this->params = array_replace_recursive($this->params, $params);
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