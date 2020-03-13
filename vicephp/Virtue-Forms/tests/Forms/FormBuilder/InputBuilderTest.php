<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class InputBuilderTest extends TestCase
{
    public function testTextInput()
    {
        $buildInput = new InputBuilder();
        $buildInput->typeText(['name' => 'aTextInput', 'type' => 'ignoreMe']);

        $expected = '{"element":"input","attributes":{"type":"text","name":"aTextInput"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testButton()
    {
        $buildInput = new InputBuilder();
        $buildInput->typeButton(['name' => 'aButton', 'value' => 'ClickMe']);

        $expected = '{"element":"input","attributes":{"type":"button","name":"aButton","value":"ClickMe"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testCheckBox()
    {
        $buildInput = new InputBuilder();
        $buildInput->typeCheckbox(['name' => 'aCheckBox', 'value' => 'checkMe']);

        $expected = '{"element":"input","attributes":{"type":"checkbox","name":"aCheckBox","value":"checkMe"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testDateTimeLocal()
    {
        $buildInput = new InputBuilder();
        $buildInput->typeDateTimeLocal(['name' => 'aDateTimeLocal']);

        $expected = '{"element":"input","attributes":{"type":"datetime-local","name":"aDateTimeLocal"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testHidden()
    {
        $buildInput = new InputBuilder();
        $buildInput->typeHidden(['name' => 'aHiddenField', 'value' => 'youDontSeeMe']);

        $expected = '{"element":"input","attributes":{"type":"hidden","name":"aHiddenField","value":"youDontSeeMe"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testRadio()
    {
        $buildInput = new InputBuilder();
        $buildInput->typeRadio(['name' => 'aRadioButton', 'value' => 'pressMe']);

        $expected = '{"element":"input","attributes":{"type":"radio","name":"aRadioButton","value":"pressMe"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }
}
