<?php

namespace Virtue\DataTables;

use PHPUnit\Framework\TestCase;

class DataTablesTest extends TestCase
{
    public function testColumn()
    {
        $column = new Column(
            [
                Column::data => 'aField',
                Column::name => 'aName',
                Column::searchable => 'true',
                Column::orderable => 'true',
                Column::search => [
                    Search::value => 'aValue',
                    Search::regex => 'true'
                ],
            ]
        );

        $this->assertEquals('aField', $column->data());
        $this->assertEquals('aName', $column->name());
        $this->assertEquals(true, $column->searchable());
        $this->assertEquals(true, $column->orderable());
    }

    public function testSearch()
    {
        $search = new Search([
            Search::value => 'aValue',
            Search::regex => ''
        ]);

        $this->assertEquals(true, $search->notEmpty());
        $this->assertEquals('aValue', $search->value());
        $this->assertEquals(false, $search->regex());
    }

    public function testOrder()
    {
        $order = new Order(
            [
                Order::column => 'aColumn',
                Order::dir => 'aDirection'
            ]
        );

        $this->assertEquals('aColumn', $order->column());
        $this->assertEquals('aDirection', $order->dir());
    }

    public function testResponseWithError()
    {
        $response = Response::withError('anError');
        $expected = [
            'draw' => 1,
            'recordsTotal' => 0,
            'recordsFiltered' => 0,
            'data' => [],
            'error' => 'anError'
        ];
        $this->assertEquals($expected, $response->jsonSerialize());
    }

    public function testResponse()
    {
        $expected = [
            'draw' => 1,
            'recordsTotal' => 3,
            'recordsFiltered' => 3,
            'data' => [
                ['aRow'], ['bRow'], ['cRow']
            ],
        ];
        $response = new Response($expected);

        $this->assertEquals($expected, $response->jsonSerialize());
    }
}
