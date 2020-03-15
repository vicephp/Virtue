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
     * @param array $attr
     * @return SelectBuilder
     */
    public function select(string $name, array $attr = []): SelectBuilder;

    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/textarea
     * @param string $name
     * @param array $attr
     * @return TextAreaBuilder
     */
    public function textArea(string $name, array $attr = []): TextAreaBuilder;
}
