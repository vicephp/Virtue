<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class OptGroupElement implements HtmlElement
{
    private $element = 'optgroup';
    /** @var array|string[] */
    private $attributes = [];
    /** @var array|OptionElement[] */
    private $options = [];

    public function __construct(array $attr, array $options = [])
    {
        Assert::string($attr['label'] ?? null, 'A label must be provided.');

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
