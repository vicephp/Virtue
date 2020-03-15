<?php

namespace Virtue\Forms\FormRenderer;

use Virtue\Forms\HtmlElement;

interface FormRenderer
{
    public function render(HtmlElement $element): string;
}
