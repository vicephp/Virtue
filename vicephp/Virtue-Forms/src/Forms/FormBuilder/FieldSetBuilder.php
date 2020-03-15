<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\FieldSetElement;
use Virtue\Forms\HtmlElement;

class FieldSetBuilder implements ElementBuilder, BuildsHtmlElement
{
    /** @var array|string[] */
    private $attributes = [];
    /** @var array|BuildsHtmlElement[] */
    private $children = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function input(): InputBuilder
    {
        return $this->children[] = new InputBuilder($this);
    }

    /**
     * @inheritDoc
     */
    public function select(string $name, array $attr = []): SelectBuilder
    {
        return $this->children[] = new SelectBuilder($name, $attr);
    }

    /**
     * @inheritDoc
     */
    public function textArea(string $name, array $attr = []): TextAreaBuilder
    {
        return $this->children[] = new TextAreaBuilder($name, $attr);
    }

    public function __invoke(): HtmlElement
    {
        return new FieldSetElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
