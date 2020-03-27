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
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/option
     * @param string $label
     * @param string $value
     * @param array $attr
     * @return BuildsOptionElement
     */
    public function option(string $label, string $value, array $attr = []): BuildsOptionElement
    {
        $this->children[] = new OptionBuilder($label, $value, $attr);

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
