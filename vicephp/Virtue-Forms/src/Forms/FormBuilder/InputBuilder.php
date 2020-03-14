<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\InputElement;

class InputBuilder
{
    private $attributes = [];

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/button
     * @param array $attributes
     */
    public function typeButton(array $attributes)
    {
        $this->attributes = ['type' => 'button'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/checkbox
     * @param array $attributes
     */
    public function typeCheckbox(array $attributes)
    {
        $this->attributes = ['type' => 'checkbox'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/radio
     * @param array $attributes
     */
    public function typeRadio(array $attributes): void
    {
        $this->attributes = ['type' => 'radio'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/search
     * @param array $attributes
     */
    public function typeSearch(array $attributes): void
    {
        $this->attributes = ['type' => 'search'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/text
     * @param array $attributes
     */
    public function typeText(array $attributes): void
    {
        $this->attributes = ['type' => 'text'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime-local
     * @param array $attributes
     */
    public function typeDateTimeLocal(array $attributes)
    {
        $this->attributes = ['type' => 'datetime-local'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/hidden
     * @param array $attributes
     */
    public function typeHidden(array $attributes)
    {
        $this->attributes = ['type' => 'hidden'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/submit
     * @param $attributes
     */
    public function typeSubmit($attributes)
    {
        $this->attributes = ['type' => 'submit'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password
     * @param array $attributes
     */
    public function typePassword(array $attributes)
    {
        $this->attributes = ['type' => 'checkbox'] + $attributes;
    }

    public function __invoke(): InputElement
    {
        return new InputElement($this->attributes);
    }
}
