<?php

namespace Virtue\Forms\FormBuilder;

use PHPUnit\Framework\TestCase;

class FormBuilderTest extends TestCase
{
    public function testApi()
    {
        $buildForm = new FormBuilder(['name' => 'aForm']);

        $expected = '{"element":"form","attributes":{"name":"aForm"},"children":[]}';
        $this->assertEquals($expected, json_encode($buildForm()));
    }
}
