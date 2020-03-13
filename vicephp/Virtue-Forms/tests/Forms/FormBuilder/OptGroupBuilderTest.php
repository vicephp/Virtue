<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class OptGroupBuilderTest extends TestCase
{
    public function testOption()
    {
        $buildOptGroup = new OptGroupBuilder(['label' => 'anOptGroup']);
        $buildOptGroup->option(['label' => 'optLabel', 'value' => 'optValue']);

        $expected = <<<JSON
{
    "element": "optgroup",
    "attributes": {
        "label": "anOptGroup"
    },
    "children": [
        {
            "element": "option",
            "attributes": {
                "label": "optLabel",
                "value": "optValue"
            }
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildOptGroup(), JSON_PRETTY_PRINT));
    }
}
