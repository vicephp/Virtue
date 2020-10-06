<?php

namespace Virtue\Database\PDO;

class Connection implements ExecutesStatements, ControlsTransactions, SupportsSequence
{
    /** @var Server */
    private $server;
    /** @var \PDO */
    private $pdo;

    public function __construct(Server $server)
    {
        $this->server = $server;
        $this->connect();
    }

    private function connect(): void
    {
        $this->pdo = $this->server->connect();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function inTransaction(): bool
    {
        return $this->pdo->inTransaction();
    }

    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    public function lastInsertId(string $name = null): string
    {
        return $this->pdo->lastInsertId($name);
    }

    public function query(string $sql): \PDOStatement
    {
        return $this->run(
            function () use ($sql) {
                return $this->pdo->query($sql);
            }
        );
    }

    public function execute(string $sql, array $params = []): \PDOStatement
    {
        return $this->run(
            function () use ($sql, $params) {
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($params);

                return $stmt;
            }
        );
    }

    private function run(callable $query): \PDOStatement
    {
        try {
            $result = $query();
        } catch (\Throwable $e) {
            $result = $this->onException($e, $query);
        }

        return $result;
    }

    private function onException(\Throwable $e, callable $query): \PDOStatement
    {
        if($this->causedByLostConnection($e) && $this->pdo->inTransaction() === false) {
            $this->connect();

            return $query();
        }

        throw $e;
    }

    private function causedByLostConnection(\Throwable $e): bool
    {
        $message = $e->getMessage();
        $needles = [
            'Broken pipe',
            'Error while sending',
            'server has gone away',
            'no connection to the server',
            'Lost connection',
            'is dead or not enabled',
            'Error while sending',
            'decryption failed or bad record mac',
            'server closed the connection unexpectedly',
            'SSL connection has been closed unexpectedly',
            'Error writing data to the connection',
            'Resource deadlock avoided',
            'Transaction() on null',
            'child connection forced to terminate due to client_idle_limit',
            'query_wait_timeout',
            'reset by peer',
        ];

        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($message, $needle) !== false) {
                return true;
            }
        }

        return false;
    }
}
