<?php

namespace Virtue\Database\PDO;

interface ControlsTransactions
{
    public function beginTransaction (): bool;
    public function commit(): bool;
    public function inTransaction(): bool;
    public function rollBack(): bool;
}
