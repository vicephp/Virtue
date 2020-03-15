<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class SelectBuilderTest extends TestCase
{
    public function testOption()
    {
        $buildSelect = new SelectBuilder('aName');
        $buildSelect->option('optLabel', 'optValue');

        $expected = <<<JSON
{
    "element": "select",
    "attributes": {
        "name": "aName"
    },
    "children": [
        {
            "element": "option",
            "attributes": {
                "value": "optValue",
                "label": "optLabel"
            }
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildSelect(), JSON_PRETTY_PRINT));
    }

    public function testOptGroup()
    {
        $buildSelect = new SelectBuilder('aName');
        $buildSelect->optGroup(['label' => 'optGroupLabel']);

        $expected = <<<JSON
{
    "element": "select",
    "attributes": {
        "name": "aName"
    },
    "children": [
        {
            "element": "optgroup",
            "attributes": {
                "label": "optGroupLabel"
            }
        }
    ]
}
JSON;

        $this->assertEquals($expected, json_encode($buildSelect(), JSON_PRETTY_PRINT));
    }
}
