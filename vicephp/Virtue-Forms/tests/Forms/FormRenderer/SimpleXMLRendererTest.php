<?php

namespace Virtue\Forms\FormRenderer;

use PHPUnit\Framework\TestCase;
use Virtue\Forms\InputElement;
use Virtue\Forms\OptionElement;
use Virtue\Forms\SelectElement;

class SimpleXMLRendererTest extends TestCase
{
    public function testRenderInputWithAttributes()
    {
        $renderer = new SimpleXMLRenderer();
        $aTextInput = InputElement::ofType('text', ['name' => 'aName', 'value' => 'aValue']);

        $this->assertEquals(
            '<input type="text" name="bName" value="aValue"/>',
            $renderer->render($aTextInput->withAttributes(['type' => 'notApplied', 'name' => 'bName']))
        );
    }

    public function testRenderInput()
    {
        $renderer = new SimpleXMLRenderer();
        $aTextInput = InputElement::ofType('text', ['name' => 'aName', 'value' => 'aValue']);

        $this->assertEquals('<input type="text" name="aName" value="aValue"/>', $renderer->render($aTextInput));
    }

    public function testRenderSelect()
    {
        $renderer = new SimpleXMLRenderer();
        $aSelect = new SelectElement(['name' => 'aName']);

        $this->assertEquals('<select name="aName"/>', $renderer->render($aSelect));

        $bSelect = new SelectElement(['name' => 'bName'], [new OptionElement(['value' => 'aValue', 'label' => 'aLabel'])]);
        $expected = '<select name="bName"><option value="aValue" label="aLabel"/></select>';
        $this->assertEquals($expected, $renderer->render($bSelect));
    }
}
