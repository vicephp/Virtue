<?php

namespace VirtueTest\Database\PDO;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Virtue\Database\PDO\Connection;
use Virtue\Database\PDO\Server;

class ConnectionTest extends MockeryTestCase
{
    public function testExecute()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $stmt->shouldReceive('execute')->with($params = ['a' => 'a', 'b' => 'b'])->andReturn(true);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('prepare')->with($sql = 'SQL')
            ->andReturn($stmt);
        $server = \Mockery::mock(Server::class);
        $server->shouldReceive('connect')->andReturn($pdo);

        $connection = new Connection($server);
        $this->assertSame($stmt, $connection->execute($sql, $params));
    }

    public function testQuery()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andReturn($stmt);
        $server = \Mockery::mock(Server::class);
        $server->shouldReceive('connect')->andReturn($pdo);

        $connection = new Connection($server);
        $this->assertSame($stmt, $connection->query($sql));
    }

    public function testReconnectOnLostConnection()
    {
        $stmt = \Mockery::mock(\PDOStatement::class);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('inTransaction')->andReturn(false);
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andThrow(new \PDOException(' server has gone away '))->once();
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andReturn($stmt);
        $server = \Mockery::mock(Server::class);
        $server->shouldReceive('connect')->andReturn($pdo);

        $connection = new Connection($server);
        $this->assertSame($stmt, $connection->query($sql));
    }

    public function testThrowException()
    {
        $this->expectException(\PDOException::class);
        $pdo = \Mockery::mock(\PDO::class);
        $pdo->shouldReceive('query')->with($sql = 'SQL')->andThrow(new \PDOException('weird stuff happened'))->once();
        $server = \Mockery::mock(Server::class);
        $server->shouldReceive('connect')->andReturn($pdo);

        $connection = new Connection($server);
        $connection->query($sql);
    }

    public function testSuccessfulTransaction(): void
    {
        $pdo = \Mockery::mock(\PDO::class);
        $server = \Mockery::mock(Server::class);
        $server->shouldReceive('connect')->andReturn($pdo);
        $connection = new Connection($server);
        $pdo->shouldReceive('beginTransaction')->andReturn(true);
        $pdo->shouldReceive('inTransaction')->andReturn(true);
        $pdo->shouldReceive('commit')->andReturn(true);

        $this->assertEquals(true, $connection->beginTransaction());
        $this->assertEquals(true, $connection->inTransaction());
        $this->assertEquals(true, $connection->commit());
    }

    public function testFailedTransaction(): void
    {
        $pdo = \Mockery::mock(\PDO::class);
        $server = \Mockery::mock(Server::class);
        $server->shouldReceive('connect')->andReturn($pdo);
        $connection = new Connection($server);
        $pdo->shouldReceive('beginTransaction')->andReturn(true);
        $pdo->shouldReceive('inTransaction')->andReturn(true);
        $pdo->shouldReceive('rollBack')->andReturn(true);

        $this->assertEquals(true, $connection->beginTransaction());
        $this->assertEquals(true, $connection->inTransaction());
        $this->assertEquals(true, $connection->rollBack());
    }
}
