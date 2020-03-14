<?php

namespace Virtue\Forms;

class TextAreaElement implements HtmlElement
{
    private $element = 'textarea';
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
