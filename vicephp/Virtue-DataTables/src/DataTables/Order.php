<?php

namespace Virtue\DataTables;

class Order
{
    const column = 'column';
    const dir = 'dir';

    /** @var Column */
    private $column;
    /** @var string */
    private $dir;

    public function __construct(Column $column, string $dir)
    {
        $this->column = $column;
        $this->dir = $dir;
    }

    public function column(): Column
    {
        return $this->column;
    }

    public function dir(): string
    {
        return $this->dir;
    }
}
