<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\InputElement;

class InputBuilder
{
    private $attributes = [];

    public function typeButton(array $attributes)
    {
        $allowed = ['id', 'name', 'value'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'button'] + $attributes;
    }

    public function typeCheckbox(array $attributes)
    {
        $allowed = ['id', 'name', 'value', 'checked'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'checkbox'] + $attributes;
    }

    public function typeRadio(array $attributes): void
    {
        $allowed = ['id', 'name', 'value', 'checked'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'radio'] + $attributes;
    }

    public function typeSearch(array $attributes): void
    {
        $allowed = ['id', 'name', 'value', 'autocomplete', 'list', 'maxlength', 'minlength', 'pattern', 'placeholder', 'required', 'size'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'radio'] + $attributes;
    }

    public function typeText(array $attributes): void
    {
        $allowed = ['id', 'name', 'list', 'minlength', 'maxlength', 'pattern', 'required', 'size'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'text'] + $attributes;
    }

    public function typeDateTimeLocal(array $attributes)
    {
        $allowed = ['id', 'name', 'value', 'min', 'max', 'step'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'datetime-local'] + $attributes;
    }

    public function typeHidden(array $attributes)
    {
        $allowed = ['id', 'name', 'value'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'hidden'] + $attributes;
    }

    public function typeSubmit($attributes)
    {
        $allowed = ['id', 'name', 'value', 'formaction', 'formenctype', 'formmethod', 'formnovalidate', 'formtarget'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'submit'] + $attributes;
    }

    public function typePassword(array $attributes)
    {
        $allowed = ['id', 'name', 'minlength', 'maxlength', 'pattern', 'required', 'size'];
        array_filter(
            $attributes,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'checkbox'] + $attributes;
    }

    public function __invoke(): InputElement
    {
        return new InputElement($this->attributes);
    }
}
