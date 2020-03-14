<?php

namespace Virtue\Forms;

interface HtmlElement extends \JsonSerializable
{
    const Element = 'element';
    const Attributes = 'attributes';
    const Children = 'children';

    public function withAttributes(array $attr): HtmlElement;
}
