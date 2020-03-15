<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class InputBuilderTest extends TestCase
{

    public function testButton()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeButton('aButton', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"button","name":"aButton","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testCheckbox()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeCheckbox('aCheckbox', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"checkbox","name":"aCheckbox","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testColor()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeColor('aColor', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"color","name":"aColor","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testDate()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeDate('aDate', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"date","name":"aDate","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testDatetimeLocal()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeDatetimeLocal('aDatetimeLocal', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"datetime-local","name":"aDatetimeLocal","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testEmail()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeEmail('aEmail', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"email","name":"aEmail","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testFile()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeFile('aFile', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"file","name":"aFile","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testHidden()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeHidden('aHidden', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"hidden","name":"aHidden","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testImage()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeImage('aImage', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"image","name":"aImage","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testMonth()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeMonth('aMonth', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"month","name":"aMonth","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testNumber()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeNumber('aNumber', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"number","name":"aNumber","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testPassword()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typePassword('aPassword', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"password","name":"aPassword","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testRadio()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeRadio('aRadioButton', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"radio","name":"aRadioButton","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testRange()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeRange('aRange', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"range","name":"aRange","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testReset()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeReset('aResetButton', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"reset","name":"aResetButton","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testSearch()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeSearch('aSearchInput', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"search","name":"aSearchInput","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testSubmit()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeSubmit('aSubmitButton', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"submit","name":"aSubmitButton","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testTel()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeTel('aTel', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"tel","name":"aTel","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testText()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeText('aTextInput', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"text","name":"aTextInput","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testTime()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeTime('aTime', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"time","name":"aTime","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testUrl()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeUrl('anUrl', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"url","name":"anUrl","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testWeek()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeWeek('aWeek', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"week","name":"aWeek","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }

    public function testDatetime()
    {
        $buildInput = new InputBuilder(new FormBuilder('aForm'));
        $buildInput->typeDatetime('aDatetime', ['value' => 'aValue']);

        $expected = '{"element":"input","attributes":{"type":"datetime","name":"aDatetime","value":"aValue"}}';
        $this->assertEquals($expected, json_encode($buildInput()));
    }
}
