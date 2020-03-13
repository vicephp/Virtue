<?php

namespace Virtue\Forms;

class TextAreaElement
{
    private $element = 'option';
    private $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function jsonSerialize(): array
    {
        return [HtmlElement::Element => $this->element, HtmlElement::Attributes => $this->attributes];
    }
}
