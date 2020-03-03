<?php

namespace VirtueTest\Database;

use PHPUnit\Framework\TestCase;
use Virtue\Database\Table;

class TableTest extends TestCase
{
    public function testWhereIdEquals()
    {
        $table = new Table([
            Table::tableName => 'aTable',
            Table::primaryKey => ['a', 'b']
        ]);
        $this->assertEquals('a = :a AND b = :b', $table->whereIdEquals());

        $table = new Table([
            Table::tableName => 'aTable',
            Table::primaryKey => ['b', 'a']
        ]);
        $this->assertEquals('b = :b AND a = :a', $table->whereIdEquals());
    }

    public function testTableName()
    {
        $table = new Table([
            Table::tableName => 'aTable',
            Table::primaryKey => []
        ]);

        $this->assertEquals('aTable', $table->tableName());
    }

    public function testPrimaryKey()
    {
        $table = new Table([
            Table::tableName => 'aTable',
            Table::primaryKey => ['a', 'b']
        ]);

        $this->assertEquals(['a', 'b'], $table->primaryKey());
        $this->assertNotEquals(['b', 'a'], $table->primaryKey());
    }

    public function testInsertColumns()
    {
        $table = new Table([
            Table::tableName => 'aTable',
            Table::primaryKey => [],
            Table::insertColumns => ['a', 'b']
        ]);

        $this->assertEquals(['a', 'b'], $table->insertColumns());
        $this->assertEquals('INSERT INTO aTable (a, b) VALUES (:a, :b)', $table->insertSql());
        $params = ['a' => 'aValue', 'b' => 'bValue', 'c' => 'cValue'];
        $this->assertEquals(['a' => 'aValue', 'b' => 'bValue'], $table->filter($params, Table::insertColumns));
    }

    public function testSelectColumns()
    {
        $table = new Table([
            Table::tableName => 'aTable',
            Table::primaryKey => [],
            Table::selectColumns => ['*']
        ]);

        $this->assertEquals(['*'], $table->selectColumns());
        $this->assertEquals('SELECT * FROM aTable WHERE 1', $table->selectSql());
    }

    public function testUpdateColumns()
    {
        $table = new Table([
            Table::tableName => 'aTable',
            Table::primaryKey => ['a'],
            Table::updateColumns => ['b', 'c']
        ]);

        $this->assertEquals(['b', 'c'], $table->updateColumns());
        $this->assertEquals('UPDATE aTable SET b = :b WHERE a = :a', $table->updateSql(['b' => 3]));
        $params = ['a' => 'aValue', 'b' => 'bValue', 'c' => 'cValue'];
        $this->assertEquals(['b' => 'bValue', 'c' => 'cValue'], $table->filter($params, Table::updateColumns));
    }
}
