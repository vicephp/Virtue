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
            new Column(
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
            ),
            'aDirection'
        );

        $this->assertEquals('aField', $order->column()->data());
        $this->assertEquals('aName', $order->column()->name());
        $this->assertEquals('aDirection', $order->dir());
    }

    public function testRequest()
    {
        $request = new Request(
            $params = [
                Request::draw => '1',
                Request::columns => [
                    [
                        Column::data => 'aField',
                        Column::name => 'aName',
                        Column::searchable => 'true',
                        Column::orderable => 'true',
                        Column::search => [
                            Search::value => 'aValue', Search::regex => 'false'
                        ],
                    ],
                ],
                Request::order => [
                    [Order::column => '0', Order::dir => 'desc']
                ],
                Request::start => '0',
                Request::length => '10',
                Request::search => [
                    Search::value => '', Search::regex => 'false'
                ],
                '_' => '1506604469131',
            ]
        );

        $this->assertEquals(1, $request->draw());
        $this->assertEquals('aField', $request->column(0)->data());
        $this->assertEquals('aName', $request->column(0)->name());
        $this->assertEquals(true, $request->column(0)->searchable());
        $this->assertEquals(true, $request->column(0)->orderable());
        $this->assertEquals(true, $request->column(0)->search()->notEmpty());
        $this->assertEquals('aValue', $request->column(0)->search()->value());
        $this->assertEquals(false, $request->column(0)->search()->regex());
        $this->assertEquals(1, count($request->columns()));
        $this->assertEquals(1, count($request->order()));
        $this->assertEquals('aField', $request->order()[0]->column()->data());
        $this->assertEquals('aName', $request->order()[0]->column()->name());
        $this->assertEquals(0, $request->start());
        $this->assertEquals(10, $request->length());
        $this->assertEquals(false, $request->search()->notEmpty());
        $this->assertEquals('', $request->search()->value());
        $this->assertEquals(false, $request->search()->regex());
        $this->assertEquals($params, $request->jsonSerialize());
    }

    public function testResponseWithError()
    {
        $response = Response::withError('anError');
        $expected = [
            Response::draw => 1,
            Response::recordsTotal => 0,
            Response::recordsFiltered => 0,
            Response::data => [],
            Response::error => 'anError'
        ];
        $this->assertEquals($expected, $response->jsonSerialize());
    }

    public function testResponse()
    {
        $expected = [
            Response::draw => 1,
            Response::recordsTotal => 3,
            Response::recordsFiltered => 3,
            Response::data => [
                ['aRow'], ['bRow'], ['cRow']
            ],
        ];
        $response = new Response($expected);

        $this->assertEquals($expected, $response->jsonSerialize());
    }
}
