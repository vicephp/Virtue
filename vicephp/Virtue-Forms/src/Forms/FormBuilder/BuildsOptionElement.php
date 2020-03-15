<?php

namespace Virtue\Forms\FormBuilder;

interface BuildsOptionElement
{
    /**
     * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/option
     * @param string $value
     * @param string $label
     * @param array $attributes
     * @return BuildsOptionElement
     */
    public function option(string $value, string $label, array $attributes = []): BuildsOptionElement;
}
