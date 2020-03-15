<?php

namespace Virtue\Forms\FormRenderer;

use Virtue\Forms\HtmlElement;

interface HtmlRenderer
{
    public function render(HtmlElement $element): string;
}
