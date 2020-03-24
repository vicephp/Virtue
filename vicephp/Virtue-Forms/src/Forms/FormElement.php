<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class FormElement implements HtmlElement
{
    private $element = 'form';
    /** @var array|string[] */
    private $attributes = [];
    /** @var array|HtmlElement[] */
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

    public function get(string $name): HtmlElement
    {
        /** @var HtmlElement $element */
        foreach ($this->elements as $element) {
            $lookup = $element->jsonSerialize()['attributes'];
            $lookup = $lookup['name'] ?? $lookup['id'] ?? '';
            if($name == $lookup) {
                return $element;
            }
        }

        throw new \OutOfBoundsException("Element with name {$name} not found.");
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
