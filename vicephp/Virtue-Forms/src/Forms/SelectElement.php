<?php

namespace Virtue\Forms;

class SelectElement implements \JsonSerializable
{
    private $element = 'select';
    private $attributes = [];
    /** @var array|OptionElement[] */
    private $options = [];

    public function __construct(array $attributes, array $options = [])
    {
        $this->attributes = $attributes;
        $this->options = $options;
    }

    public function jsonSerialize()
    {
        return ['element' => $this->element, 'attributes' => $this->attributes, 'options' => $this->options];
    }
}
