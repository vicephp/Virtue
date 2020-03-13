<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class OptGroupElement implements HtmlElement
{
    private $element = 'optgroup';
    private $attributes = [];
    private $options = [];

    public function __construct(array $attr, array $options = [])
    {
        Assert::string($attr['label'] ?? null, 'A label must be provided.');

        $this->attributes = $attr;
        $this->options = $options;
    }

    public function jsonSerialize(): array
    {
        return [HtmlElement::Element => $this->element, HtmlElement::Attributes => $this->attributes, HtmlElement::Children => $this->options];
    }
}
