<?php

namespace Virtue\Forms;

class TextAreaElement implements HtmlElement
{
    private $element = 'textarea';
    /** @var array|string[] */
    private $attributes = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function withAttributes(array $attr): HtmlElement
    {
        return new self (array_replace($this->attributes, $attr));
    }

    public function jsonSerialize(): array
    {
        return [HtmlElement::Element => $this->element, HtmlElement::Attributes => $this->attributes];
    }
}
