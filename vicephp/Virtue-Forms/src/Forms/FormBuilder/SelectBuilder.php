<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\HtmlElement;
use Virtue\Forms\SelectElement;

class SelectBuilder implements BuildsHtmlElement, BuildsOptionElement
{
    /** @var array|string[]  */
    private $attributes = [];
    /** @var array|BuildsHtmlElement[] */
    private $children = [];

    public function __construct(string $name, array $attributes = [])
    {
        $this->attributes = ['name' => $name] + $attributes;
    }

    /**
     * @inheritDoc
     */
    public function option(string $value, string $label, array $attr = []): BuildsOptionElement
    {
        $this->children[] = new OptionBuilder($value, $label, $attr);

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

    public function __invoke(): HtmlElement
    {
        return new SelectElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
