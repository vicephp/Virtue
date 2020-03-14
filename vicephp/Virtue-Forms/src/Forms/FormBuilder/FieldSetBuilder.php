<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\FieldSetElement;

class FieldSetBuilder implements ElementBuilder
{
    private $attributes = [];
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
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select
     * @param $attributes
     * @return SelectBuilder
     */
    public function select($attributes): SelectBuilder
    {
        return $this->children[] = new SelectBuilder($attributes);
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
     * @param $attributes
     * @return TextAreaBuilder
     */
    public function textArea($attributes): TextAreaBuilder
    {
        return $this->children[] = new TextAreaBuilder($attributes);
    }

    public function __invoke(): FieldSetElement
    {
        return new FieldSetElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
