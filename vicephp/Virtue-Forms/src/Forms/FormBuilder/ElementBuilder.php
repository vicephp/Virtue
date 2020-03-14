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
     * @param array $attributes
     * @return SelectBuilder
     */
    public function select(array $attributes): SelectBuilder;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
     * @param array $attributes
     * @return TextAreaBuilder
     */
    public function textArea(array $attributes): TextAreaBuilder;
}
