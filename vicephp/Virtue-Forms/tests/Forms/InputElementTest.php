<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class InputElementTest extends TestCase
{
    public function testOfType()
    {
        $aTextInput = InputElement::ofType('text', ['name' => 'aTextInput']);
        $this->assertEquals(['element' => 'input', 'attributes' => ['type' => 'text', 'name' => 'aTextInput']], $aTextInput->jsonSerialize());
    }

    public function testHasNameOrId()
    {
        $aTextInput = InputElement::ofType('text', ['id' => 'anId']);
        $expected = ['element' => 'input', 'attributes' => ['type' => 'text', 'name' => 'anId', 'id' => 'anId']];
        $this->assertEquals($expected, $aTextInput->jsonSerialize());

        $bTextInput = InputElement::ofType('text', ['name' => 'bName', 'id' => 'bId']);
        $this->assertEquals(['element' => 'input', 'attributes' => ['type' => 'text', 'name' => 'bName', 'id' => 'bId']], $bTextInput->jsonSerialize());

        $this->expectException(\InvalidArgumentException::class);
        $cTextInput = InputElement::ofType('text', ['withNoIdOrName']);
    }
}
