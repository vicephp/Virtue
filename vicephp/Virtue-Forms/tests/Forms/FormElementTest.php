<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class FormElementTest extends TestCase
{

    public function testJsonSerialize()
    {
        $aFormElement = new FormElement(['id' => 'anId']);
        $this->assertEquals('{"element":"form","attributes":{"id":"anId","name":"anId"},"inner":[]}', json_encode($aFormElement));

        $bFormElement = new FormElement(['name' => 'bName', 'id' => 'bId']);
        $this->assertEquals('{"element":"form","attributes":{"name":"bName","id":"bId"},"inner":[]}', json_encode($bFormElement));

        $this->expectException(\InvalidArgumentException::class);
        $cFormElement = new FormElement(['withNoIdOrName']);
    }
}
