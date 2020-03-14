<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\FormElement;

class FormBuilder
{
    private $attributes = [];
    private $children = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function fieldSet($attributes): FieldSetBuilder
    {
        return $this->children[] = new FieldSetBuilder($attributes);
    }

    public function input(): InputBuilder
    {
        return $this->children[] = new InputBuilder();
    }

    public function select($attributes): SelectBuilder
    {
        return $this->children[] = new SelectBuilder($attributes);
    }

    public function textArea($attributes): TextAreaBuilder
    {
        return $this->children[] = new TextAreaBuilder($attributes);
    }

    public function __invoke(): FormElement
    {
        return new FormElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
