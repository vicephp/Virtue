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

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/option
     * @param array $attributes
     * @return SelectBuilder
     */
    public function option(array $attributes): SelectBuilder
    {
        $this->children[] = new OptionBuilder($attributes);

        return $this;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/optgroup
     * @param array $attributes
     * @return OptGroupBuilder
     */
    public function optGroup(array $attributes): OptGroupBuilder
    {
        return $this->children[] = new OptGroupBuilder($attributes);
    }

    public function __invoke(): SelectElement
    {
        return new SelectElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
