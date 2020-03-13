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
    }
}
