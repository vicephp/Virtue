<?php

namespace Virtue\Database\PDO;

class MockStatementExecutor implements ExecutesStatements, \Countable
{
    /** @var array */
    private $queue = [];
    /** @var string */
    private $lastStatement;
    /** @var array */
    private $lastParams;

    public function __construct(array $queue = [])
    {
        $this->append(...array_values($queue));
    }

    public function query(string $sql): \PDOStatement
    {
        return $this->execute($sql);
    }

    public function execute(string $sql, array $params = []): \PDOStatement
    {
        if (!$this->queue) {
            throw new \OutOfBoundsException('Mock queue is empty');
        }
        $this->lastStatement = $sql;
        $this->lastParams = $params;
        $response = \array_shift($this->queue);

        if (\is_callable($response)) {
            $response = $response($sql, $params);
        }

        if($response instanceof \Throwable) {
            throw $response;
        }

        return $response;
    }

    public function append(...$responses): void
    {
        foreach ($responses as $response) {
            if ($response instanceof \PDOStatement
                || $response instanceof \Throwable
                || \is_callable($response)
            ) {
                $this->queue[] = $response;
            } else {
                throw new \TypeError('Expected a PDOStatement, Throwable or callable. Found ' . \gettype($response));
            }
        }
    }

    public function lastStatement(): ?string
    {
        return $this->lastStatement;
    }

    public function lastParams(): ?array
    {
        return $this->lastParams;
    }

    public function count(): int
    {
        return \count($this->queue);
    }

    public function reset(): void
    {
        $this->queue = [];
    }
}
