<?php

namespace Virtue\Forms;

use Webmozart\Assert\Assert;

class InputElement implements HtmlElement
{
    private $element = 'input';
    private $attributes = [];

    /**
     * @param array $attr
     */
    private function __construct(array $attr)
    {
        $attr['name'] = $attr['name'] ?? $attr['id'] ?? null;
        Assert::string($attr['name'], 'A name or id must be provided');
        $this->attributes = $attr;
    }

    public static function ofType($type, array $attr)
    {
        unset($attr['type']);
        return new self(['type' => $type] + $attr);
    }

    public function withAttributes(array $attr): self
    {
        unset($attr['type']);
        return new self (array_replace($this->attributes, $attr));
    }

    public function jsonSerialize(): array
    {
        return ['element' => $this->element, 'attributes' => $this->attributes];
    }
}
