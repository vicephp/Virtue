<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\HtmlElement;
use Virtue\Forms\TextAreaElement;

class TextAreaBuilder implements BuildsHtmlElement
{
    /** @var array|string[]  */
    private $attributes = [];

    public function __construct(string $name, array $attr = [])
    {
        $this->attributes = ['name' => $name] + $attr;
    }

    public function __invoke(): HtmlElement
    {
        return new TextAreaElement($this->attributes);
    }
}
