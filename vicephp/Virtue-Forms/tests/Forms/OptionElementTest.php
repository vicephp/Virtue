<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class OptionElementTest extends TestCase
{
    public function testJsonSerialize()
    {
        $anOptionElement =  new OptionElement(['value' => 'aValue', 'label' => 'aLabel']);

        $this->assertEquals('{"element":"option","attributes":{"value":"aValue","label":"aLabel"}}', json_encode($anOptionElement));
    }
}
