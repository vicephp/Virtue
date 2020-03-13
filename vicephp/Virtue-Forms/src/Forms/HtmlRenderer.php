<?php

namespace Virtue\Forms;

class HtmlRenderer
{
    public function render(HtmlElement $element, $attr = []): string
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
