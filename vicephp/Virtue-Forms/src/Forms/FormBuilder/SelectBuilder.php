<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\SelectElement;

class SelectBuilder
{
    private $attributes = [];
    private $children = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function option(array $attributes): SelectBuilder
    {
        $this->children[] = new OptionBuilder($attributes);

        return $this;
    }

    public function optGroup(array $attributes): OptGroupBuilder
    {
        return $this->children[] = new OptGroupBuilder($attributes);
    }

    public function __invoke(): SelectElement
    {
        return new SelectElement(
            $this->attributes,
            array_map(function ($child) { return $child(); }, $this->children)
        );
    }
}
