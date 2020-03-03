<?php

namespace Virtue\Database\PDO;

interface ExecutesStatements
{
    public function query(string $sql): \PDOStatement;
    public function execute(string $sql, array $params = []): \PDOStatement;
}
