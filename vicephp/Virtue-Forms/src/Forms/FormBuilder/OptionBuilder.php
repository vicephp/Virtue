<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\OptionElement;

class OptionBuilder
{
    private $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __invoke(): OptionElement
    {
        return new OptionElement($this->attributes);
    }
}
