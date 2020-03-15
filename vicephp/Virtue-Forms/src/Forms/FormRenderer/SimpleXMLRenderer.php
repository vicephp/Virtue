<?php

namespace Virtue\Forms\FormRenderer;

use Virtue\Forms\HtmlElement;

class SimpleXMLRenderer implements HtmlRenderer
{
    public function render(HtmlElement $element): string
    {
        $element = $this->renderElement(new \SimpleXMLElement("<body></body>"), $element);

        return $element->asXML();
    }

    private function renderElement(\SimpleXMLElement $parent, HtmlElement $child): \SimpleXMLElement
    {
        $child = $child->jsonSerialize();
        $node = $parent->addChild($child[HtmlElement::Element]);
        foreach ($child[HtmlElement::Attributes] as $attr => $val) {
            $node->addAttribute($attr, $val);
        }
        foreach ($child[HtmlElement::Children] ?? [] as $inner) {
            $this->renderElement($node, $inner);
        }
        return $node;
    }
}
