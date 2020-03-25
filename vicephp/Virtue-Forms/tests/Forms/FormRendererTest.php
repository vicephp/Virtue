<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;
use Virtue\Forms\FormRenderer\SimpleXMLRenderer;

class FormRendererTest extends TestCase
{
    public function testRender()
    {
        $buildForm = new FormBuilder\FormBuilder('aForm');
        $buildForm->input()->typeText('aTextField');

        /** @var FormElement $form */
        $form = $buildForm();
        $form = new FormRenderer($form, new SimpleXMLRenderer());

        $this->assertEquals(
            '<input type="text" name="aTextField" class="someCssClass"/>',
            $form->render('aTextField', ['class' => 'someCssClass'])
        );

    }
}
