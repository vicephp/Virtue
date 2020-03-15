<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class InputElement implements HtmlElement
{
    private $element = 'input';
    /** @var array|string[] */
    private $attributes = [];

    public function __construct(array $attr)
    {
        Assert::string($attr['type'] ?? null, 'A type must be provided');
        $attr['name'] = $attr['name'] ?? $attr['id'] ?? null;
        Assert::string($attr['name'], 'A name or id must be provided');

        $this->attributes = $attr;
    }

    public function withAttributes(array $attr): HtmlElement
    {
        unset($attr['type']);
        return new self (array_replace($this->attributes, $attr));
    }

    public function jsonSerialize(): array
    {
        return [HtmlElement::Element => $this->element, HtmlElement::Attributes => $this->attributes];
    }
}
