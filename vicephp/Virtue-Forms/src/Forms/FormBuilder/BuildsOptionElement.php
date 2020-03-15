<?php

namespace Virtue\Forms\FormBuilder;

interface BuildsOptionElement
{
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/option
     * @param string $label
     * @param string $value
     * @param array $attr
     * @return BuildsOptionElement
     */
    public function option(string $label, string $value, array $attr = []): BuildsOptionElement;
}
