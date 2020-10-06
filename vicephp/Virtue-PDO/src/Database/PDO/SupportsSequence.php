<?php

namespace Virtue\Database\PDO;

interface SupportsSequence
{
    public function lastInsertId(string $name = null): string;
}
