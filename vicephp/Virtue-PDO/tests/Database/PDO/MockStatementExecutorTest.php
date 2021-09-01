<?php

namespace Virtue\Database\PDO;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class MockStatementExecutorTest extends TestCase
{
    public function testExecute()
    {
        $sql = new MockStatementExecutor();
        $sql->append(\Mockery::mock(\PDOStatement::class));
        $sql->execute('<sql>', ['<p>' => '<v>']);

        Assert::assertEquals('<sql>', $sql->lastStatement());
        Assert::assertEquals(['<p>' => '<v>'], $sql->lastParams());
        Assert::assertCount(0, $sql);
    }

    public function testQuery()
    {
        $sql = new MockStatementExecutor();
        $sql->append(\Mockery::mock(\PDOStatement::class));
        $sql->query('<sql>');

        Assert::assertEquals('<sql>', $sql->lastStatement());
        Assert::assertEquals([], $sql->lastParams());
        Assert::assertCount(0, $sql);
    }

    public function testEmptyQueueThrowsException()
    {
        $this->expectException(\OutOfBoundsException::class);
        $sql = new MockStatementExecutor();
        $sql->execute('<spq>', ['<p>' => '<v>']);
    }

    public function testAppendThrowsTypeError()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Expected a PDOStatement, Throwable or callable. Found array');
        $sql = new MockStatementExecutor();
        $sql->append([]);
    }

    public function testCallableAsHandler()
    {
        $sql = new MockStatementExecutor();
        $sql->append(function (string $sql, array $options) {
            Assert::assertEquals('<sql>', $sql);
            Assert::assertEquals(['<p>' => '<v>'], $options);
            return \Mockery::mock(\PDOStatement::class);
        });

        $sql->execute('<sql>', ['<p>' => '<v>']);
        Assert::assertCount(0, $sql);
    }

    public function testThrowableIsThrown()
    {
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('<message>');
        $sql = new MockStatementExecutor();
        $sql->append(new \PDOException('<message>'));
        $sql->execute('<sql>', ['<p>' => '<v>']);
    }

    public function testCount()
    {
        $sql = new MockStatementExecutor();
        $sql->append(\Mockery::mock(\PDOStatement::class));
        $sql->append(\Mockery::mock(\PDOStatement::class));
        Assert::assertCount(2, $sql);
    }

    public function testReset()
    {
        $sql = new MockStatementExecutor();
        $sql->append(\Mockery::mock(\PDOStatement::class));
        $sql->reset();
        Assert::assertCount(0, $sql);
    }
}
