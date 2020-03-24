<?php

namespace Virtue\Forms;

class FieldSetElement implements HtmlElement
{
    private $element = 'fieldset';
    /** @var array|string[] */
    private $attributes = [];
    /** @var array|HtmlElement[] */
    private $elements = [];

    public function __construct(array $attr, array $elements = [])
    {
        $this->attributes = $attr;
        $this->elements = $elements;
    }

    public function withAttributes(array $attr): HtmlElement
    {
        return new self(array_replace($this->attributes, $attr), $this->elements);
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
