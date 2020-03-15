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
        $buildForm->select('aSelectField')
            ->option('aValue', 'aLabel')
            ->option('bValue', 'bLabel')
            ->option('cValue', 'cLabel');

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
                        "value": "aValue",
                        "label": "aLabel"
                    }
                },
                {
                    "element": "option",
                    "attributes": {
                        "value": "bValue",
                        "label": "bLabel"
                    }
                },
                {
                    "element": "option",
                    "attributes": {
                        "value": "cValue",
                        "label": "cLabel"
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
        $buildForm->textArea('aTextArea');

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
