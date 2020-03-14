<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\InputElement;

class InputBuilder
{
    /** @var ElementBuilder */
    private $parent;
    private $attributes = [];

    public function __construct(ElementBuilder $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/button
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeButton(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'button'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/checkbox
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeCheckbox(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'checkbox'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/color
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeColor(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'color'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/date
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeDate(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'date'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime-local
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeDatetimeLocal(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'datetime-local'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/email
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeEmail(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'email'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/file
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeFile(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'file'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/hidden
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeHidden(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'hidden'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/image
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeImage(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'image'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/month
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeMonth(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'month'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/number
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeNumber(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'number'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typePassword(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'password'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/radio
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeRadio(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'radio'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/range
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeRange(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'range'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/reset
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeReset(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'reset'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/search
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeSearch(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'search'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/submit
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeSubmit(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'submit'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/tel
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeTel(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'tel'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/text
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeText(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'text'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/time
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeTime(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'time'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/url
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeUrl(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'url'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/week
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeWeek(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'week'] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeDatetime(array $attributes): ElementBuilder
    {
        $this->attributes = ['type' => 'datetime'] + $attributes;

        return $this->parent;
    }

    public function __invoke(): InputElement
    {
        return new InputElement($this->attributes);
    }
}
