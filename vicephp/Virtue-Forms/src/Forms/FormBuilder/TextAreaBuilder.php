<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\TextAreaElement;

class TextAreaBuilder
{
    private $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __invoke(): TextAreaElement
    {
        return new TextAreaElement($this->attributes);
    }
}
