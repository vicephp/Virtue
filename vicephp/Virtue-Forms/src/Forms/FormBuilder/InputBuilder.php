<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\HtmlElement;
use Virtue\Forms\InputElement;

class InputBuilder implements BuildsHtmlElement
{
    /** @var ElementBuilder */
    private $parent;
    /** @var array|string[] */
    private $attributes = [];

    public function __construct(ElementBuilder $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/button
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeButton(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'button', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/checkbox
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeCheckbox(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'checkbox', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/color
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeColor(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'color', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/date
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeDate(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'date', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime-local
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeDatetimeLocal(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'datetime-local', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/email
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeEmail(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'email', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/file
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeFile(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'file', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/hidden
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeHidden(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'hidden', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/image
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeImage(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'image', 'name' => $name, 'alt' => $attributes['alt'] ?? $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/month
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeMonth(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'month', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/number
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeNumber(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'number', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/password
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typePassword(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'password', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/radio
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeRadio(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'radio', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/range
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeRange(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'range', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/reset
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeReset(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'reset', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/search
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeSearch(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'search', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/submit
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeSubmit(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'submit', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/tel
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeTel(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'tel', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/text
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeText(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'text', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/time
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeTime(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'time', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/url
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeUrl(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'url', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/week
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeWeek(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'week', 'name' => $name] + $attributes;

        return $this->parent;
    }

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/datetime
     * @param string $name
     * @param array $attributes
     * @return ElementBuilder
     */
    public function typeDatetime(string $name, array $attributes = []): ElementBuilder
    {
        $this->attributes = ['type' => 'datetime', 'name' => $name] + $attributes;

        return $this->parent;
    }

    public function __invoke(): HtmlElement
    {
        return new InputElement($this->attributes);
    }
}
