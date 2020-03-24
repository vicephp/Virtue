<?php

namespace VirtueTest\Database\PDO;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Virtue\Database;

class TableGatewayTest extends MockeryTestCase
{
    public function testById()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $stmt->shouldReceive('fetchAll')->andReturn($rows = [['first']]);
        $adapter = \Mockery::mock(Database\PDO\Connection::class);
        $adapter->shouldReceive('execute')
            ->with('SELECT * FROM aTable WHERE id = :id', $id = ['id' => 'anId'])
            ->andReturn($stmt);
        $table = new Database\PDO\TableGateway(
            $adapter,
            new Database\Table([
                Database\Table::tableName => 'aTable',
                Database\Table::selectColumns => ['*'],
                Database\Table::primaryKey => ['id']
            ])
        );

        $this->assertEquals($rows[0], $table->byId($id));
    }

    public function testAll()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $stmt->shouldReceive('fetchAll')->andReturn($rows = [['firstRow'], ['secondRow']]);
        $adapter = \Mockery::mock(Database\PDO\Connection::class);
        $adapter->shouldReceive('execute')
            ->andReturnUsing(function ($where, $params) use ($stmt) {
                $this->assertEquals("SELECT * FROM aTable WHERE foo = :bar", $where);
                $this->assertEquals(['bar' => 'foo'], $params);

                return $stmt;
            });
        $table = new Database\PDO\TableGateway(
            $adapter,
            new Database\Table([
                Database\Table::tableName => 'aTable',
                Database\Table::primaryKey => ['id'],
                Database\Table::selectColumns => ['*'],
            ])
        );

        $this->assertEquals($rows, $table->all('foo = :bar', ['bar' => 'foo']));
    }

    public function testInsert()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $adapter = \Mockery::mock(Database\PDO\Connection::class);
        $adapter->shouldReceive('execute')
            ->with('INSERT INTO aTable (a, b) VALUES (:a, :b)', $params = ['a' => 1, 'b' => 2])
            ->andReturn($stmt);
        $table = new Database\PDO\TableGateway(
            $adapter,
            new Database\Table([
                Database\Table::tableName => 'aTable',
                Database\Table::primaryKey => [],
                Database\Table::insertColumns => ['a', 'b'],
            ])
        );

        $table->insert($params);
    }

    public function testUpdate()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $adapter = \Mockery::mock(Database\PDO\Connection::class);
        $adapter->shouldReceive('execute')
            ->with('UPDATE aTable SET a = :a, b = :b WHERE id = :id', ['id' => 'foo', 'a' => 1, 'b' => 2])
            ->andReturn($stmt);
        $table = new Database\PDO\TableGateway(
            $adapter,
            new Database\Table([
                Database\Table::tableName => 'aTable',
                Database\Table::primaryKey => ['id'],
                Database\Table::updateColumns => ['a', 'b'],
            ])
        );

        $table->update(['id' => 'foo'], ['a' => 1, 'b' => 2]);
    }

    public function testDelete()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $adapter = \Mockery::mock(Database\PDO\Connection::class);
        $adapter->shouldReceive('execute')
            ->with('DELETE FROM aTable WHERE id = :id', ['id' => 'foo'])
            ->andReturn($stmt);
        $table = new Database\PDO\TableGateway(
            $adapter,
            new Database\Table([
                Database\Table::tableName => 'aTable',
                Database\Table::primaryKey => ['id']
            ])
        );

        $table->delete(['id' => 'foo']);
    }
}
