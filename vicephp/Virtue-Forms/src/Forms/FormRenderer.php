<?php

namespace Virtue\Forms;

use Virtue\Forms\FormRenderer\HtmlRenderer;

class FormRenderer
{
    /** @var FormElement */
    private $form;
    /** @var HtmlRenderer */
    private $html;

    public function __construct(FormElement $form, HtmlRenderer $html)
    {
        $this->form = $form;
        $this->html = $html;
    }

    public function render(string $name, $attr = []): string
    {
        return $this->html->render(
            $this->form->get($name)->withAttributes($attr)
        );
    }
}
