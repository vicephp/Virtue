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
        $node = $parent->addChild($child['element']);
        foreach ($child['attributes'] as $attr => $val) {
            $node->addAttribute($attr, $val);
        }
        foreach ($child['inner'] ?? [] as $inner) {
            $this->renderElement($node, $inner);
        }
        return $node;
    }
}
