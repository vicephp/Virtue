<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class FormBuilderTest extends TestCase
{
    public function testApi()
    {
        $buildForm = new FormBuilder(['name' => 'aForm']);

        $expected = '{"element":"form","attributes":{"name":"aForm"}}';
        $this->assertEquals($expected, json_encode($buildForm()));

        $buildForm->input()->typeText(['name' => 'aTextInput']);
        $expected = <<<JSON
{
    "element": "form",
    "attributes": {
        "name": "aForm"
    },
    "children": [
        {
            "element": "input",
            "attributes": {
                "type": "text",
                "name": "aTextInput"
            }
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildForm(), JSON_PRETTY_PRINT));

        $buildForm->select(['name' => 'aSelectField'])
            ->option(['label' => 'aLabel', 'value' => 'aValue'])
            ->option(['label' => 'bLabel', 'value' => 'bValue']);

        $expected = <<<JSON
{
    "element": "form",
    "attributes": {
        "name": "aForm"
    },
    "children": [
        {
            "element": "input",
            "attributes": {
                "type": "text",
                "name": "aTextInput"
            }
        },
        {
            "element": "select",
            "attributes": {
                "name": "aSelectField"
            },
            "children": [
                {
                    "element": "option",
                    "attributes": {
                        "label": "aLabel",
                        "value": "aValue"
                    }
                },
                {
                    "element": "option",
                    "attributes": {
                        "label": "bLabel",
                        "value": "bValue"
                    }
                }
            ]
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildForm(), JSON_PRETTY_PRINT));
    }
}
