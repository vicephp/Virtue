<?php

namespace Virtue\Forms\FormRenderer;

use PHPUnit\Framework\TestCase;
use Virtue\Forms\InputElement;
use Virtue\Forms\OptionElement;
use Virtue\Forms\SelectElement;

class DOMDocumentRendererTest extends TestCase
{
    public function testRenderInput()
    {
        $renderer = new DOMDocumentRenderer();
        $aTextInput = new InputElement(['type' => 'text', 'name' => 'aName', 'value' => 'aValue']);

        $this->assertEquals(
            '<input type="text" name="bName" value="aValue"/>',
            $renderer->render($aTextInput->withAttributes(['type' => 'notApplied', 'name' => 'bName']))
        );
    }

    public function testRenderSelect()
    {
        $renderer = new DOMDocumentRenderer();
        $aSelect = new SelectElement(['name' => 'aName']);

        //$this->assertEquals('<select name="aName"/>', $renderer->render($aSelect));

        $bSelect = new SelectElement(['name' => 'bName'], [new OptionElement(['value' => 'aValue', 'label' => 'aLabel'])]);

        $expected = <<<HTML
<select name="bName">
  <option value="aValue" label="aLabel"/>
</select>
HTML;
        $this->assertEquals($expected, $renderer->render($bSelect));
    }
}
