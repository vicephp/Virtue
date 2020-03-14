<?php

namespace Virtue\Forms\FormBuilder;

interface ElementBuilder
{
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input
     * @return InputBuilder
     */
    public function input(): InputBuilder;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/select
     * @param $attributes
     * @return SelectBuilder
     */
    public function select($attributes): SelectBuilder;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
     * @param $attributes
     * @return TextAreaBuilder
     */
    public function textArea($attributes): TextAreaBuilder;
}
