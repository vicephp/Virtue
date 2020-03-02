<?php

namespace Virtue\DataTables;

class Order
{
    private $column;
    /** @var string */
    private $dir;

    public function __construct($params)
    {
        $this->column = $params['column'];
        $this->dir = $params['dir'];
    }

    public function column(): string
    {
        return $this->column;
    }

    public function dir(): string
    {
        return $this->dir;
    }
}
