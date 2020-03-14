<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class TextAreaElementTest extends TestCase
{
    public function testWithAttributes()
    {
        $aTextArea = new TextAreaElement(['name' => 'aTextArea']);
        $bTextArea = $aTextArea->withAttributes(['name' => 'bTextArea']);

        $this->assertEquals('{"element":"textarea","attributes":{"name":"aTextArea"}}', json_encode($aTextArea));
        $this->assertEquals('{"element":"textarea","attributes":{"name":"bTextArea"}}', json_encode($bTextArea));
    }
}
