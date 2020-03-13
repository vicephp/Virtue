<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class OptionElement
{
    private $element = 'option';
    private $attributes = [];

    private function __construct(array $attributes)
    {
        Assert::string($attributes['value'] ?? null);
        $this->attributes = $attributes;
    }
}
