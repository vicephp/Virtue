<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class OptionElementTest extends TestCase
{
    public function testWithAttributes()
    {
        $anOption =  new OptionElement(['value' => 'aValue', 'label' => 'aLabel']);
        $bOption = $anOption->withAttributes(['value' => 'bValue', 'label' => 'bLabel']);

        $this->assertEquals('{"element":"option","attributes":{"value":"aValue","label":"aLabel"}}', json_encode($anOption));
        $this->assertEquals('{"element":"option","attributes":{"value":"bValue","label":"bLabel"}}', json_encode($bOption));
    }
}
