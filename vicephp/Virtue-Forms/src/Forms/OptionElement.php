<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class OptionElement implements HtmlElement
{
    private $element = 'option';
    private $attributes = [];

    /**
     * @param array $attr ['value' => 'aValue', 'label' => 'aLabel' [, ...]]
     */
    public function __construct(array $attr)
    {
        Assert::string($attr['label'] ?? null, 'A label must be provided.');
        Assert::string($attr['value'] ?? null, 'A value must be provided.');

        $this->attributes = $attr;
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
