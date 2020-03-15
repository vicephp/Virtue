<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class InputElementTest extends TestCase
{
    public function testHasNameOrId()
    {
        $aTextInput = new InputElement(['type' => 'text', 'id' => 'anId']);
        $this->assertEquals('{"element":"input","attributes":{"type":"text","id":"anId","name":"anId"}}', json_encode($aTextInput));

        $bTextInput = new InputElement(['type' => 'text','name' => 'bName', 'id' => 'bId']);
        $this->assertEquals('{"element":"input","attributes":{"type":"text","name":"bName","id":"bId"}}', json_encode($bTextInput));

        $this->expectException(\InvalidArgumentException::class);
        $cTextInput = new InputElement(['type' => 'text', 'withNoIdOrName']);
    }
}
