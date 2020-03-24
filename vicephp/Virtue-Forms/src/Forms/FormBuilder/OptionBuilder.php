<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\HtmlElement;
use Virtue\Forms\OptionElement;

class OptionBuilder implements BuildsHtmlElement
{
    /** @var array|string[]  */
    private $attributes = [];

    public function __construct(string $label, string $value, array $attributes = [])
    {
        $this->attributes = ['value' => $value, 'label' => $label] + $attributes;
    }

    public function __invoke(): HtmlElement
    {
        return new OptionElement($this->attributes);
    }
}
