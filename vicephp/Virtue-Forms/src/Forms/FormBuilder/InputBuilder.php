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
    public function typeButton(array $attributes): void
    {
        $this->attributes = ['type' => 'button'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/checkbox
     * @param array $attributes
     */
    public function typeCheckbox(array $attributes): void
    {
        $this->attributes = ['type' => 'checkbox'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/color
     * @param array $attributes
     */
    public function typeColor(array $attributes): void
    {
        $this->attributes = ['type' => 'color'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/date
     * @param array $attributes
     */
    public function typeDate(array $attributes): void
    {
        $this->attributes = ['type' => 'date'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime-local
     * @param array $attributes
     */
    public function typeDatetimeLocal(array $attributes): void
    {
        $this->attributes = ['type' => 'datetime-local'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/email
     * @param array $attributes
     */
    public function typeEmail(array $attributes): void
    {
        $this->attributes = ['type' => 'email'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/file
     * @param array $attributes
     */
    public function typeFile(array $attributes): void
    {
        $this->attributes = ['type' => 'file'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/hidden
     * @param array $attributes
     */
    public function typeHidden(array $attributes): void
    {
        $this->attributes = ['type' => 'hidden'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/image
     * @param array $attributes
     */
    public function typeImage(array $attributes): void
    {
        $this->attributes = ['type' => 'image'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/month
     * @param array $attributes
     */
    public function typeMonth(array $attributes): void
    {
        $this->attributes = ['type' => 'month'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/number
     * @param array $attributes
     */
    public function typeNumber(array $attributes): void
    {
        $this->attributes = ['type' => 'number'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password
     * @param array $attributes
     */
    public function typePassword(array $attributes): void
    {
        $this->attributes = ['type' => 'password'] + $attributes;
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
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/range
     * @param array $attributes
     */
    public function typeRange(array $attributes): void
    {
        $this->attributes = ['type' => 'range'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/reset
     * @param array $attributes
     */
    public function typeReset(array $attributes): void
    {
        $this->attributes = ['type' => 'reset'] + $attributes;
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
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/submit
     * @param array $attributes
     */
    public function typeSubmit(array $attributes): void
    {
        $this->attributes = ['type' => 'submit'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/tel
     * @param array $attributes
     */
    public function typeTel(array $attributes): void
    {
        $this->attributes = ['type' => 'tel'] + $attributes;
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
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/time
     * @param array $attributes
     */
    public function typeTime(array $attributes): void
    {
        $this->attributes = ['type' => 'time'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/url
     * @param array $attributes
     */
    public function typeUrl(array $attributes): void
    {
        $this->attributes = ['type' => 'url'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/week
     * @param array $attributes
     */
    public function typeWeek(array $attributes): void
    {
        $this->attributes = ['type' => 'week'] + $attributes;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime
     * @param array $attributes
     */
    public function typeDatetime(array $attributes): void
    {
        $this->attributes = ['type' => 'datetime'] + $attributes;
    }

    public function __invoke(): InputElement
    {
        return new InputElement($this->attributes);
    }
}
