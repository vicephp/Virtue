<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\FormElement;

class FormBuilder implements ElementBuilder
{
    private $attributes = [];
    private $children = [];

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/form
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/fieldset
     * @param array $attributes
     * @return FieldSetBuilder
     */
    public function fieldSet(array $attributes): FieldSetBuilder
    {
        return $this->children[] = new FieldSetBuilder($attributes);
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
     * @param array $attributes
     * @return SelectBuilder
     */
    public function select(array $attributes): SelectBuilder
    {
        return $this->children[] = new SelectBuilder($attributes);
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
     * @param array $attributes
     * @return TextAreaBuilder
     */
    public function textArea(array $attributes): TextAreaBuilder
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
