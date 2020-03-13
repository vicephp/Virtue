<?php

namespace Virtue\Forms;

class HtmlRenderer
{
    public function render(HtmlElement $element, $attr = []): string
    {
        $body = new \SimpleXMLElement("<body></body>");
        $element = json_decode(json_encode($element), true);
        $node = $this->renderNode($body->addChild($element['element']), $element);

        return $node->asXML();
    }

    private function renderNode(\SimpleXMLElement $node, $element): \SimpleXMLElement
    {
        foreach ($element['attributes'] as $attr => $val) {
            $node->addAttribute($attr, $val);
        }
        foreach ($element['inner'] ?? [] as $inner) {
            $this->renderNode($node->addChild($inner['element']), $inner);
        }
        return $node;
    }

}
