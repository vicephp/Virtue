<?php

namespace Virtue\Forms\FormRenderer;

use Virtue\Forms\HtmlElement;

class DOMDocumentRenderer implements HtmlRenderer
{
    /** @var \DOMDocument */
    private $dom;

    public function render(HtmlElement $element): string
    {
        $this->dom = new \DOMDocument('1.0');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = true;
        $element = $this->renderElement($this->dom, $element);

        return $this->dom->saveXML($element);
    }

    private function renderElement(\DOMNode $parent, HtmlElement $child): \DOMNode
    {
        $child = $child->jsonSerialize();
        $node = $this->dom->createElement($child[HtmlElement::Element]);
        $parent->appendChild($node);
        foreach ($child[HtmlElement::Attributes] as $attr => $val) {
            $node->setAttribute($attr, $val);
        }
        foreach ($child[HtmlElement::Children] ?? [] as $inner) {
            $this->renderElement($node, $inner);
        }

        return $node;
    }
}
