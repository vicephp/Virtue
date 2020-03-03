<?php

namespace Virtue\DataTables;

class Order
{
    const column = 'column';
    const dir = 'dir';

    private $column;
    /** @var string */
    private $dir;

    public function __construct($params)
    {
        $this->column = $params[self::column];
        $this->dir = $params[self::dir];
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
