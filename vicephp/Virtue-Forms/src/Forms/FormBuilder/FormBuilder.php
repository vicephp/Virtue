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

    public function input(): InputBuilder
    {
        return $this->children[] = new InputBuilder();
    }

    public function select(): SelectBuilder
    {
        return $this->children[] = new SelectBuilder();
    }

    public function __invoke(): FormElement
    {
        return new FormElement(
            $this->attributes,
            array_map(function ($child) { return $child(); }, $this->children)
        );
    }
}
