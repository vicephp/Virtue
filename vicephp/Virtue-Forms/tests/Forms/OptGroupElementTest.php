<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class OptGroupElementTest extends TestCase
{
    public function testOptions()
    {
        $anOptGroup = new OptGroupElement(['label' => 'anOptGroup'], [new OptionElement(['value' => 'aValue', 'label' => 'aLabel'])]);
        $expected = <<<JSON
{
    "element": "optgroup",
    "attributes": {
        "label": "anOptGroup"
    },
    "children": [
        {
            "element": "option",
            "attributes": {
                "value": "aValue",
                "label": "aLabel"
            }
        }
    ]
}
JSON;
        $this->assertEquals($expected, json_encode($anOptGroup, JSON_PRETTY_PRINT));
    }

    public function testWithAttributes()
    {
        $anOptGroup = new OptGroupElement(['label' => 'anOptGroup']);
        $bOptGroup = $anOptGroup->withAttributes(['label' => 'bOptGroup']);

        $this->assertEquals('{"element":"optgroup","attributes":{"label":"anOptGroup"}}', json_encode($anOptGroup));
        $this->assertEquals('{"element":"optgroup","attributes":{"label":"bOptGroup"}}', json_encode($bOptGroup));
    }
}
