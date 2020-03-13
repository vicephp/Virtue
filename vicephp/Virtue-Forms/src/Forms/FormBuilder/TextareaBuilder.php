<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\TextareaElement;

class TextareaBuilder
{
    private $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __invoke(): TextareaElement
    {
        return new TextareaElement($this->attributes);
    }
}
