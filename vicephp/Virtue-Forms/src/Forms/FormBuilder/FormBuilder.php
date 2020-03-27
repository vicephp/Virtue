<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\FormElement;
use Virtue\Forms\HtmlElement;

class FormBuilder implements ElementBuilder, BuildsHtmlElement
{
    /** @var array|string[] */
    private $attributes = [];
    /** @var array|BuildsHtmlElement[] */
    private $children = [];

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/form
     * @param string $name
     * @param array $attributes
     */
    public function __construct(string $name, array $attributes = [])
    {
        $this->attributes = ['name' => $name] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/fieldset
     * @param array $attr
     * @return FieldSetBuilder
     */
    public function fieldSet(array $attr = []): FieldSetBuilder
    {
        return $this->children[] = new FieldSetBuilder($attr);
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input
     * @return InputBuilder
     */
    public function input(): InputBuilder
    {
        return $this->children[] = new InputBuilder($this);
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select
     * @param string $name
     * @param array $attr
     * @return SelectBuilder
     */
    public function select(string $name, array $attr = []): SelectBuilder
    {
        return $this->children[] = new SelectBuilder($name, $attr);
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
     * @param string $name
     * @param array $attr
     * @return TextAreaBuilder
     */
    public function textArea(string $name, array $attr = []): TextAreaBuilder
    {
        return $this->children[] = new TextAreaBuilder($name, $attr);
    }

    public function __invoke(): HtmlElement
    {
        return new FormElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
