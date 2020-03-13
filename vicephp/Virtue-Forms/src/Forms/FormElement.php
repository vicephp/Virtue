<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class FormElement implements HtmlElement
{
    private $element = 'form';
    private $attributes = [];
    private $elements = [];

    /**
     * @param array $attr ['id' => 'anId' [, ...]] or ['name' => 'aName' [, ...]]
     * @param array $elements
     */
    public function __construct(array $attr, array $elements = [])
    {
        $attr['name'] = $attr['name'] ?? $attr['id'] ?? null;
        Assert::string($attr['name'], 'A name or id must be provided');
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
