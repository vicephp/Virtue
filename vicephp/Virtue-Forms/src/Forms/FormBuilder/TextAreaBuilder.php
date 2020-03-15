<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\HtmlElement;
use Virtue\Forms\TextAreaElement;

class TextAreaBuilder implements BuildsHtmlElement
{
    /** @var array|string[]  */
    private $attributes = [];

    public function __construct(string $name, array $attributes = [])
    {
        $this->attributes = ['name' => $name] + $attributes;
    }

    public function __invoke(): HtmlElement
    {
        return new TextAreaElement($this->attributes);
    }
}
