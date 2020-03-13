<?php

namespace Virtue\Forms;

class SelectElement implements HtmlElement
{
    private $element = 'select';
    private $attributes = [];
    /** @var array|OptionElement[] */
    private $options = [];

    public function __construct(array $attr, array $options = [])
    {
        $this->attributes = $attr;
        $this->options = $options;
    }

    public function jsonSerialize(): array
    {
        return [HtmlElement::Element => $this->element, HtmlElement::Attributes => $this->attributes, HtmlElement::Children => $this->options];
    }
}
