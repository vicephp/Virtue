<?php

namespace Virtue\DataTables;

class Column
{
    const data = 'data';
    const name = 'name';
    const searchable = 'searchable';
    const orderable = 'orderable';
    const search = 'search';

    /** @var string */
    private $name;
    /** @var string */
    private $data;
    /** @var bool */
    private $searchable;
    /** @var bool */
    private $orderable;
    /** @var Search */
    private $search;

    public function __construct($data)
    {
        $this->name = $data[self::name];
        $this->data = $data[self::data];
        $this->searchable = $data[self::searchable] === 'true';
        $this->orderable = $data[self::orderable] === 'true';
        $this->search = new Search($data[self::search]);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function data(): string
    {
        return $this->data;
    }

    public function searchable(): bool
    {
        return $this->searchable;
    }

    public function orderable(): bool
    {
        return $this->orderable;
    }

    public function search(): Search
    {
        return $this->search;
    }
}
