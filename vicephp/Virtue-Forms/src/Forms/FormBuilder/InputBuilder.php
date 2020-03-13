<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\InputElement;

class InputBuilder
{
    private $attributes = [];

    public function typeButton(array $attr)
    {
        $allowed = ['id', 'name', 'value'];
        array_filter(
            $attr,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'button'] + $attr;
    }

    public function typeCheckbox(array $attr)
    {
        $allowed = ['id', 'name', 'value', 'checked'];
        array_filter(
            $attr,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'checkbox'] + $attr;
    }

    public function typeText(array $attr): void
    {
        $allowed = ['id', 'name', 'list', 'minlength', 'maxlength', 'pattern', 'required', 'size'];
        array_filter(
            $attr,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'text'] + $attr;
    }

    public function typeDateTimeLocal(array $attr)
    {
        $allowed = ['id', 'name', 'value', 'min', 'max', 'step'];
        array_filter(
            $attr,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'datetime-local'] + $attr;
    }


    public function typePassword(array $attr)
    {
        $allowed = ['id', 'name', 'minlength', 'maxlength', 'pattern', 'required', 'size'];
        array_filter(
            $attr,
            function ($attr) use ($allowed) { return in_array($attr, $allowed); },
            ARRAY_FILTER_USE_KEY
        );
        $this->attributes = ['type' => 'checkbox'] + $attr;
    }

    public function __invoke(): InputElement
    {
        return new InputElement($this->attributes);
    }
}
