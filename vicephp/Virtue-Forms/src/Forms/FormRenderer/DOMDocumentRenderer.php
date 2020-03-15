<?php

namespace Virtue\Forms\FormRenderer;

use Virtue\Forms\HtmlElement;

class DOMDocumentRenderer implements HtmlRenderer
{
    /** @var \DOMDocument */
    private $dom;

    private $settings = [
        'version' => '1.0',
        'encoding' => 'utf-8',
        'preserveWhiteSpace' => false,
        'formatOutput' => true,
    ];

    public function render(HtmlElement $element): string
    {
        $this->dom = new \DOMDocument($this->settings['version'], $this->settings['encoding']);
        $this->dom->preserveWhiteSpace = $this->settings['preserveWhiteSpace'];
        $this->dom->formatOutput = $this->settings['formatOutput'];
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
        if($node->hasChildNodes() === false && $this->childRequired($node)) {
            $node->appendChild($this->dom->createTextNode(''));
        }

        return $node;
    }

    private function childRequired(\DOMNode $node): bool
    {
        return in_array($node->nodeName, ['textarea', 'select', 'optgroup', 'form']);
    }
}
