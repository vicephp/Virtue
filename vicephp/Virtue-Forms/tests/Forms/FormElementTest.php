<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class FormElementTest extends TestCase
{
    public function testJsonSerialize()
    {
        $aFormElement = new FormElement(['id' => 'anId']);
        $this->assertEquals('{"element":"form","attributes":{"id":"anId","name":"anId"}}', json_encode($aFormElement));

        $bFormElement = new FormElement(['name' => 'bName', 'id' => 'bId']);
        $this->assertEquals('{"element":"form","attributes":{"name":"bName","id":"bId"}}', json_encode($bFormElement));

        $this->expectException(\InvalidArgumentException::class);
        $cFormElement = new FormElement(['withNoIdOrName']);
    }

    public function testGet()
    {
        $aFormElement = new FormElement(['id' => 'anId'], [new InputElement(['type' => 'text', 'name' => 'anInput'])]);

        $this->assertInstanceOf(InputElement::class, $aFormElement->get('anInput'));
    }
}
