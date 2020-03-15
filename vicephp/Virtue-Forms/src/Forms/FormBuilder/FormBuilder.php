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
     * @param array $attributes
     * @return FieldSetBuilder
     */
    public function fieldSet(array $attributes = []): FieldSetBuilder
    {
        return $this->children[] = new FieldSetBuilder($attributes);
    }

    /**
     * @inheritDoc
     */
    public function input(): InputBuilder
    {
        return $this->children[] = new InputBuilder($this);
    }

    /**
     * @inheritDoc
     */
    public function select(string $name, array $attributes = []): SelectBuilder
    {
        return $this->children[] = new SelectBuilder($name, $attributes);
    }

    /**
     * @inheritDoc
     */
    public function textArea(string $name, array $attributes = []): TextAreaBuilder
    {
        return $this->children[] = new TextAreaBuilder($name, $attributes);
    }

    public function __invoke(): HtmlElement
    {
        return new FormElement(
            $this->attributes,
            array_map(function ($buildChild) { return $buildChild(); }, $this->children)
        );
    }
}
