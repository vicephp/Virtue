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
     * @param string $name
     * @param array $attributes
     * @return SelectBuilder
     */
    public function select(string $name, array $attributes = []): SelectBuilder;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
     * @param string $name
     * @param array $attributes
     * @return TextAreaBuilder
     */
    public function textArea(string $name, array $attributes = []): TextAreaBuilder;
}
