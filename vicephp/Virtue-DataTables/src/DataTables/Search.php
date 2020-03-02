<?php

namespace Virtue\DataTables;

class Search
{
    const value = 'value';
    const regex = 'regex';

    private $value;
    private $regex = false;

    public function __construct($params)
    {
        $this->value = $params[self::value];
        $this->regex = $params[self::regex] == 'true';
    }

    public function notEmpty(): bool
    {
        return $this->value <> '';
    }

    public function value(): string
    {
        return $this->value;
    }

    public function regex(): bool
    {
        return $this->regex;
    }
}
