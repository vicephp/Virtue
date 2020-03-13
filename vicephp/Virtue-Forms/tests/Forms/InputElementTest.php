<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class InputElementTest extends TestCase
{
    public function testOfType()
    {
        $aTextInput = InputElement::ofType('text', ['name' => 'aTextInput']);
        $this->assertEquals('{"element":"input","attributes":{"type":"text","name":"aTextInput"}}', json_encode($aTextInput));
    }

    public function testHasNameOrId()
    {
        $aTextInput = InputElement::ofType('text', ['id' => 'anId']);
        $this->assertEquals('{"element":"input","attributes":{"type":"text","id":"anId","name":"anId"}}', json_encode($aTextInput));

        $bTextInput = InputElement::ofType('text', ['name' => 'bName', 'id' => 'bId']);
        $this->assertEquals('{"element":"input","attributes":{"type":"text","name":"bName","id":"bId"}}', json_encode($bTextInput));

        $this->expectException(\InvalidArgumentException::class);
        $cTextInput = InputElement::ofType('text', ['withNoIdOrName']);
    }
}
