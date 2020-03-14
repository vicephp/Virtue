<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\OptGroupElement;

class OptGroupBuilder
{
    private $attributes = [];
    private $children = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function option(array $attributes): OptGroupBuilder
    {
        $this->children[] = new OptionBuilder($attributes);

        return $this;
    }

    public function __invoke(): OptGroupElement
    {
        return new OptGroupElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
