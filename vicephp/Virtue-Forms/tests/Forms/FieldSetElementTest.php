<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class FieldSetElementTest extends TestCase
{
    public function testWithAttributes()
    {
        $aFieldSet = new FieldSetElement(['name' => 'aFieldSet']);
        $bFieldSet = $aFieldSet->withAttributes(['name' => 'bFieldSet']);

        $this->assertEquals('{"element":"fieldset","attributes":{"name":"aFieldSet"}}', json_encode($aFieldSet));
        $this->assertEquals('{"element":"fieldset","attributes":{"name":"bFieldSet"}}', json_encode($bFieldSet));
    }
}
