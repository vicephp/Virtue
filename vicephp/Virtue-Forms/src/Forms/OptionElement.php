<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class OptionElement implements HtmlElement
{
    private $element = 'option';
    private $attributes = [];

    /**
     * ['value' => 'aValue', 'label' => 'aLabel']
     * @param array $attr
     */
    public function __construct(array $attr)
    {
        Assert::string($attr['label'] ?? null, 'A label must be provided.');
        Assert::string($attr['value'] ?? null, 'A value must be provided.');

        $this->attributes = $attr;
    }

    public function jsonSerialize(): array
    {
        return ['element' => $this->element, 'attributes' => $this->attributes];
    }
}
