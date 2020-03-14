<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class SelectElementTest extends TestCase
{
    public function testOptions()
    {
        $select = new SelectElement(['name' => 'aSelectBox'], [new OptionElement(['value' => 'aValue', 'label' => 'aLabel'])]);
        $expected = '{"element":"select","attributes":{"name":"aSelectBox"},"children":[{"element":"option","attributes":{"value":"aValue","label":"aLabel"}}]}';
        $this->assertEquals($expected, json_encode($select->jsonSerialize()));
    }

    public function testWithAttributes()
    {
        $aSelectBox = new SelectElement(['name' => 'aSelectBox']);
        $bSelectBox = $aSelectBox->withAttributes(['name' => 'bSelectBox']);

        $this->assertEquals('{"element":"select","attributes":{"name":"aSelectBox"}}', json_encode($aSelectBox));
        $this->assertEquals('{"element":"select","attributes":{"name":"bSelectBox"}}', json_encode($bSelectBox));
    }
}
