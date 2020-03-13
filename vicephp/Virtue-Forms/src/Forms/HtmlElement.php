<?php

namespace Virtue\Forms;

interface HtmlElement extends \JsonSerializable
{
    const Element = 'element';
    const Attributes = 'attributes';
    const Children = 'inner';
}
