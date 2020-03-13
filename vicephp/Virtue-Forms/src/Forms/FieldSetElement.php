<?php

namespace Virtue\Forms;

class FieldSetElement implements HtmlElement
{
    private $element = 'fieldset';
    private $attributes = [];
    private $elements = [];

    public function __construct(array $attr, array $elements = [])
    {
        $this->attributes = $attr;
        $this->elements = $elements;
    }

    public function jsonSerialize(): array
    {
        return array_filter(
            [
                HtmlElement::Element => $this->element,
                HtmlElement::Attributes => $this->attributes,
                HtmlElement::Children => $this->elements
            ]
        );
    }
}
