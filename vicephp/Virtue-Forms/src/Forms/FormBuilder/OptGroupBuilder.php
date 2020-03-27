<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\HtmlElement;
use Virtue\Forms\OptGroupElement;

class OptGroupBuilder implements BuildsHtmlElement, BuildsOptionElement
{
    /** @var array|string[] */
    private $attributes = [];
    /** @var array|BuildsHtmlElement[] */
    private $children = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
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

    public function __invoke(): HtmlElement
    {
        return new OptGroupElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
