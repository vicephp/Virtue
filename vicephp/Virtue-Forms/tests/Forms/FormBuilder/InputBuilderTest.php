<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class InputBuilderTest extends TestCase
{
    public function testButton()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeButton(['name' => 'aButton', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"button","name":"aButton","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testCheckbox()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeCheckbox(['name' => 'aCheckbox', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"checkbox","name":"aCheckbox","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testColor()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeColor(['name' => 'aColor', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"color","name":"aColor","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testDate()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeDate(['name' => 'aDate', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"date","name":"aDate","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testDatetimeLocal()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeDatetimeLocal(['name' => 'aDatetimeLocal', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"datetime-local","name":"aDatetimeLocal","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testEmail()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeEmail(['name' => 'aEmail', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"email","name":"aEmail","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testFile()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeFile(['name' => 'aFile', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"file","name":"aFile","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testHidden()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeHidden(['name' => 'aHidden', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"hidden","name":"aHidden","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testImage()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeImage(['name' => 'aImage', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"image","name":"aImage","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testMonth()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeMonth(['name' => 'aMonth', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"month","name":"aMonth","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testNumber()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeNumber(['name' => 'aNumber', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"number","name":"aNumber","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testPassword()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typePassword(['name' => 'aPassword', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"password","name":"aPassword","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testRadio()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeRadio(['name' => 'aRadio', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"radio","name":"aRadio","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testRange()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeRange(['name' => 'aRange', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"range","name":"aRange","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testReset()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeReset(['name' => 'aResetButton', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"reset","name":"aResetButton","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testSearch()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeSearch(['name' => 'aSearchInput', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"search","name":"aSearchInput","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testSubmit()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeSubmit(['name' => 'aSubmitButton', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"submit","name":"aSubmitButton","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testTel()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeTel(['name' => 'aTel', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"tel","name":"aTel","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testText()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeText(['name' => 'aTextInput', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"text","name":"aTextInput","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testTime()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeTime(['name' => 'aTime', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"time","name":"aTime","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testUrl()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeUrl(['name' => 'anUrl', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"url","name":"anUrl","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testWeek()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeWeek(['name' => 'aWeek', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"week","name":"aWeek","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testDatetime()
    {
        $buildInput = new InputBuilder(new FormBuilder(['name' => 'aForm']));
        $buildInput->typeDatetime(['name' => 'aDatetime', 'value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"datetime","name":"aDatetime","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }
}
