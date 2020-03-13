<?php

namespace Virtue\Forms;

class HtmlRenderer
{
    private $renderers = [];

    public function render(HtmlElement $element, $attr = []): string
    {
        $element = $element->jsonSerialize();

        $html = [$this->openingTag($element), $this->innerHtml($element), $this->closingTag($element)];
        $html = array_filter($html);
        return implode('', $html);
    }

    private function openingTag($element)
    {
        $element['attributes'] = implode(
            ' ',
            array_reduce(
                array_keys($element['attributes']),
                function ($attribs, $key) {
                    $attribs[$key] = sprintf('%s="%s"', $key, $attribs[$key]);
                    return $attribs;
                },
                $element['attributes']
            )
        );
        $element = trim("{$element['element']} {$element['attributes']}");
        return "<{$element}>";
    }

    private function innerHtml($element): string
    {
        $html = '';
        foreach ($element['inner'] ?? [] as $inner) {
            $html .= $this->render($inner);
        }
        return $html;
    }

    private function closingTag($element){
        return in_array($element['element'], ['input']) ? '' : "</{$element['element']}>";
    }
}
