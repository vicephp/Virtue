<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class SelectBuilderTest extends TestCase
{
    public function testOption()
    {
        $buildSelect = new SelectBuilder(['name' => 'aName']);
        $buildSelect->option(['label' => 'optLabel', 'value' => 'optValue']);

        $expected = '{"element":"select","attributes":{"name":"aName"},"children":[{"element":"option","attributes":{"label":"optLabel","value":"optValue"}}]}';
        $this->assertEquals($expected, json_encode($buildSelect()));
    }

    public function testOptGroup()
    {
        $buildSelect = new SelectBuilder(['name' => 'aName']);
        $buildSelect->optGroup(['label' => 'optGroupLabel']);

        $expected = '{"element":"select","attributes":{"name":"aName"},"children":[{"element":"optgroup","attributes":{"label":"optGroupLabel"},"children":[]}]}';
        $this->assertEquals($expected, json_encode($buildSelect()));
    }
}
