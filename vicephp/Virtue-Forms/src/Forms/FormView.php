<?php

namespace Virtue\Forms;

class FormView
{
    /** @var FormRenderer\HtmlRenderer */
    private $html;

    public function __construct(FormRenderer\HtmlRenderer $renderer)
    {
        $this->html = $renderer;
    }

    public function selectElement(string $name, array $options, array $attr = [])
    {
        $options = $this->buildOptions($options);
        return $this->html->render(
            new SelectElement(['name' => $name] + $attr, $options)
        );
    }

    private function buildOptions($options): array
    {
        foreach ($options as $label => $value) {
            $options[$label] = is_array($value) ?
                new OptGroupElement(['label' => $label], $this->buildOptions($value)) :
                new OptionElement(['value' => $value, 'label' => $label]);
        }

        return $options;
    }

    public function buttonInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'button', 'name' => $name] + $attr)
        );
    }

    public function checkboxInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'checkbox', 'name' => $name] + $attr)
        );
    }

    public function colorInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'color', 'name' => $name] + $attr)
        );
    }

    public function dateInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'date', 'name' => $name] + $attr)
        );
    }

    public function dateTimeLocalInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'datetime-local', 'name' => $name] + $attr)
        );
    }

    public function emailInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'email', 'name' => $name] + $attr)
        );
    }

    public function fileInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'file', 'name' => $name] + $attr)
        );
    }

    public function hiddenInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'hidden', 'name' => $name] + $attr)
        );
    }

    public function imageInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'image', 'name' => $name, 'alt' => $attr['alt'] ?? $name] + $attr)
        );
    }

    public function monthInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'month', 'name' => $name] + $attr)
        );
    }

    public function numberInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'number', 'name' => $name] + $attr)
        );
    }

    public function passwordInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'password', 'name' => $name] + $attr)
        );
    }

    public function radioInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'radio', 'name' => $name] + $attr)
        );
    }

    public function rangeInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'range', 'name' => $name] + $attr)
        );
    }

    public function resetInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'reset', 'name' => $name] + $attr)
        );
    }

    public function searchInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'search', 'name' => $name] + $attr)
        );
    }

    public function submitInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'submit', 'name' => $name] + $attr)
        );
    }

    public function telInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'tel', 'name' => $name] + $attr)
        );
    }

    public function textInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'text', 'name' => $name] + $attr)
        );
    }

    public function timeInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'time', 'name' => $name] + $attr)
        );
    }

    public function urlInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'url', 'name' => $name] + $attr)
        );
    }

    public function weekInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'week', 'name' => $name] + $attr)
        );
    }

    public function datetimeInput(string $name, array $attr = []): string
    {
        return $this->html->render(
            new InputElement(['type' => 'datetime', 'name' => $name] + $attr)
        );
    }
}
