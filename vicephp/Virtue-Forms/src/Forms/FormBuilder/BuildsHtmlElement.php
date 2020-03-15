<?php

namespace Virtue\Forms\FormBuilder;

use Virtue\Forms\HtmlElement;

interface BuildsHtmlElement
{
    public function __invoke(): HtmlElement;
}
