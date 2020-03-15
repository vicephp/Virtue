<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class FieldSetBuilderTest extends TestCase
{
    public function testInput()
    {
        $buildFieldSet = new FieldSetBuilder(['name' => 'aFieldSet']);
        $buildFieldSet->input()->typeText('aTextInput');

        $expected = <<<JSON
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
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildFieldSet(), JSON_PRETTY_PRINT));
    }

    public function testSelect()
    {
        $buildFieldSet = new FieldSetBuilder(['name' => 'aFieldSet']);
        $buildFieldSet->select(['name' => 'aSelectBox']);

        $expected = <<<JSON
{
    "element": "fieldset",
    "attributes": {
        "name": "aFieldSet"
    },
    "children": [
        {
            "element": "select",
            "attributes": {
                "name": "aSelectBox"
            }
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($buildFieldSet(), JSON_PRETTY_PRINT));
    }

    public function testTextArea()
    {
        $buildFieldSet = new FieldSetBuilder(['name' => 'aFieldSet']);
        $buildFieldSet->textArea(['name' => 'aTextArea']);

        $expected = <<<JSON
{
    "element": "fieldset",
    "attributes": {
        "name": "aFieldSet"
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
        $this->assertEquals($expected, json_encode($buildFieldSet(), JSON_PRETTY_PRINT));
    }
}
