<?php

namespace Virtue\DataTables;

class Request implements \JsonSerializable
{
    const search = 'search';
    const start = 'start';
    const length = 'length';
    const draw = 'draw';
    const columns = 'columns';
    const order = 'order';

    private $params = [
        Request::draw => 0,
        Request::columns => [],
        Request::order => [],
        Request::start => 0,
        Request::length => 100,
        Request::search => [Search::value => '', Search::regex => 'false'],
    ];

    /** @var Column[] */
    private $columns;
    /** @var Search */
    private $search;
    /** @var Order[] */
    private $order;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = array_replace_recursive($this->params, $params);
        $this->columns = array_map(
            function ($column) {
                return new Column($column);
            },
            $this->params[self::columns]
        );
        $this->order = array_map(
            function ($order) {
                return new Order($this->column($order[Order::column]), $order[Order::dir]);
            },
            $this->params[self::order]
        );
        $this->search = new Search($this->params[self::search]);
    }

    /**
     * @return array|Order[]
     */
    public function order(): array
    {
        return $this->order;
    }

    /**
     * @return array|Column[]
     */
    public function columns(): array
    {
        return $this->columns;
    }

    public function column($idx): Column
    {
        return $this->columns[$idx];
    }

    public function search(): Search
    {
        return $this->search;
    }

    public function jsonSerialize()
    {
        return $this->params;
    }

    public function start()
    {
        return $this->params[self::start];
    }

    public function length()
    {
        return $this->params[self::length];
    }

    public function draw()
    {
        return $this->params[self::draw];
    }
}
