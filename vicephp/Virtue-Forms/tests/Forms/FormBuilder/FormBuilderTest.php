<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class FormBuilderTest extends TestCase
{
    public function testForm()
    {
        $buildForm = new FormBuilder('aForm');

        $expected = '{"element":"form","attributes":{"name":"aForm"}}';
        $this->assertEquals($expected, json_encode($buildForm()));
    }

    public function testFieldSet()
    {
        $buildForm = new FormBuilder('aForm');
        $buildForm->fieldSet(['name' => 'aFieldSet'])
            ->input()->typeText('aTextInput')
            ->input()->typeText('bTextInput');

        $expected = <<<JSON
{
    "element": "form",
    "attributes": {
        "name": "aForm"
    },
    "children": [
        {
            "element": "fieldset",
            "attributes": {
                "name": "aFieldSet"
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
                    "element": "input",
                    "attributes": {
                        "type": "text",
                        "name": "bTextInput"
                    }
                }
            ]
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildForm(), JSON_PRETTY_PRINT));
    }

    public function testInput()
    {
        $buildForm = new FormBuilder('aForm');
        $buildForm->input()->typeText('aTextInput');

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
    }

    public function testSelect()
    {
        $buildForm = new FormBuilder('aForm');
        $buildForm->select(['name' => 'aSelectField'])
            ->option(['label' => 'aLabel', 'value' => 'aValue'])
            ->option(['label' => 'bLabel', 'value' => 'bValue'])
            ->option(['label' => 'cLabel', 'value' => 'cValue']);

        $expected = <<<JSON
{
    "element": "form",
    "attributes": {
        "name": "aForm"
    },
    "children": [
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
                },
                {
                    "element": "option",
                    "attributes": {
                        "label": "cLabel",
                        "value": "cValue"
                    }
                }
            ]
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildForm(), JSON_PRETTY_PRINT));
    }

    public function testTextArea()
    {
        $buildForm = new FormBuilder('aForm');
        $buildForm->textArea(['name' => 'aTextArea']);

        $expected = <<<JSON
{
    "element": "form",
    "attributes": {
        "name": "aForm"
    },
    "children": [
        {
            "element": "textarea",
            "attributes": {
                "name": "aTextArea"
            }
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildForm(), JSON_PRETTY_PRINT));
    }
}
