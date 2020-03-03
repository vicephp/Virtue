<?php

namespace VirtueTest\Database\PDO;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Virtue\Database\PDO\Connection;
use Virtue\Database\PDO\Server;

class AdapterTest extends MockeryTestCase
{
    public function testExecute()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $stmt->shouldReceive('execute')->with($params = ['a' => 'a', 'b' => 'b'])->andReturn(true);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('prepare')->with($sql = 'SQL')
            ->andReturn($stmt);
        $connection = \Mockery::mock(Server::class);
        $connection->shouldReceive('connect')->andReturn($pdo);

        $adapter = new Connection($connection);
        $this->assertSame($stmt, $adapter->execute($sql, $params));
    }

    public function testQuery()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andReturn($stmt);
        $connection = \Mockery::mock(Server::class);
        $connection->shouldReceive('connect')->andReturn($pdo);

        $adapter = new Connection($connection);
        $this->assertSame($stmt, $adapter->query($sql));
    }

    public function testReconnectOnLostConnection()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('inTransaction')->andReturn(false);
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andThrow(new \PDOException(' server has gone away '))->once();
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andReturn($stmt);
        $connection = \Mockery::mock(Server::class);
        $connection->shouldReceive('connect')->andReturn($pdo);

        $adapter = new Connection($connection);
        $this->assertSame($stmt, $adapter->query($sql));
    }

    public function testThrowException()
    {
        $this->expectException(\PDOException::class);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andThrow(new \PDOException('weird stuff happened'))->once();
        $connection = \Mockery::mock(Server::class);
        $connection->shouldReceive('connect')->andReturn($pdo);

        $adapter = new Connection($connection);
        $adapter->query($sql);
    }
}
