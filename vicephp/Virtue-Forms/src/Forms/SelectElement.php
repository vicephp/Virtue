<?php

namespace Virtue\Forms;

class SelectElement implements HtmlElement
{
    private $element = 'select';
    /** @var array|string[] */
    private $attributes = [];
    /** @var array|OptionElement[]|OptGroupElement[] */
    private $options = [];

    public function __construct(array $attr, array $options = [])
    {
        $this->attributes = $attr;
        $this->options = $options;
    }

    public function withAttributes(array $attr): HtmlElement
    {
        return new self(array_replace($this->attributes, $attr), $this->options);
    }

    public function jsonSerialize(): array
    {
        return array_filter(
            [
                HtmlElement::Element => $this->element,
                HtmlElement::Attributes => $this->attributes,
                HtmlElement::Children => $this->options
            ]
        );
    }
}
