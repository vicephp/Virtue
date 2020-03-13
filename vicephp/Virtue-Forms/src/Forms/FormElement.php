<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class FormElement implements HtmlElement
{
    private $element = 'form';
    private $attributes = [];
    private $inner = [];

    /**
     * @param array $attr ['id' => 'anId' [, ...]] or ['name' => 'aName' [, ...]]
     */
    public function __construct(array $attr)
    {
        $attr['name'] = $attr['name'] ?? $attr['id'] ?? null;
        Assert::string($attr['name'], 'A name or id must be provided');
        $this->attributes = $attr;
    }

    public function add(HtmlElement $element)
    {
        $this->inner[] = $element;
    }

    public function jsonSerialize(): array
    {
        return ['element' => $this->element, 'attributes' => $this->attributes, 'inner' => $this->inner];
    }
}
