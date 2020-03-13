<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class HtmlRendererTest extends TestCase
{
    public function testRenderInput()
    {
        $renderer = new HtmlRenderer();
        $aTextInput = InputElement::ofType('text', ['name' => 'aName', 'value' => 'aValue']);

        $this->assertEquals('<input type="text" name="aName" value="aValue">', $renderer->render($aTextInput));
    }

    public function testRenderSelect()
    {
        $renderer = new HtmlRenderer();
        $aSelect = new SelectElement(['name' => 'aName']);

        $this->assertEquals('<select name="aName"></select>', $renderer->render($aSelect));

        $bSelect = new SelectElement(['name' => 'bName'], [new OptionElement(['value' => 'aValue', 'label' => 'aLabel'])]);
        $expected = '<select name="bName"><option value="aValue" label="aLabel"></option></select>';
        $this->assertEquals($expected, $renderer->render($bSelect));
    }
}
