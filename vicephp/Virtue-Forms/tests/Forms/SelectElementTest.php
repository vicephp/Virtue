<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class SelectElementTest extends TestCase
{
    public function testJsonSerialize()
    {
        $select = new SelectElement(['name' => 'aSelectElement']);

        $this->assertEquals('{"element":"select","attributes":{"name":"aSelectElement"}}', json_encode($select->jsonSerialize()));
    }

    public function testWithOptions()
    {
        $select = new SelectElement(['name' => 'aSelectElement'], [new OptionElement(['value' => 'aValue', 'label' => 'aLabel'])]);
        $expected = '{"element":"select","attributes":{"name":"aSelectElement"},"children":[{"element":"option","attributes":{"value":"aValue","label":"aLabel"}}]}';
        $this->assertEquals($expected, json_encode($select->jsonSerialize()));
    }
}
