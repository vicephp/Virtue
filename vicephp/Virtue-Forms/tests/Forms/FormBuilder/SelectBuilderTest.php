<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class SelectBuilderTest extends TestCase
{
    public function testOption()
    {
        $buildSelect = new SelectBuilder(['name' => 'aName']);
        $buildSelect->option(['label' => 'optLabel', 'value' => 'optValue']);

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
                "label": "optLabel",
                "value": "optValue"
            }
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildSelect(), JSON_PRETTY_PRINT));
    }

    public function testOptGroup()
    {
        $buildSelect = new SelectBuilder(['name' => 'aName']);
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
