<?php

namespace Virtue\Database;

use Assert\Assertion as Assert;

class Table
{
    const tableName = 'tableName';
    const primaryKey = 'primaryKey';
    const insertColumns = 'insertColumns';
    const selectColumns = 'selectColumns';
    const updateColumns = 'updateColumns';

    /** @var array */
    private $config = [
        self::selectColumns => ['*'],
        self::insertColumns => [],
        self::updateColumns => [],
    ];

    public function __construct($config)
    {
        $this->assertConfigIsValid($config);
        $this->config = array_replace_recursive($this->config, $config);
    }

    private function assertConfigIsValid($config)
    {
        Assert::keyIsset($config, self::tableName, 'Table name not set. Please provide a table name.');
        Assert::string($config[self::tableName], 'Table name has to a a string.');
        Assert::keyIsset($config, self::primaryKey, 'Primary key not set. Please provide a primary key.');
        Assert::isArray($config[self::primaryKey], 'Primary has to be a collection of column names.');
    }

    public function tableName(): string
    {
        return $this->config[self::tableName];
    }

    public function primaryKey(): array
    {
        return $this->config[self::primaryKey];
    }

    public function insertColumns(): array
    {
        return $this->config[self::insertColumns];
    }

    public function selectColumns(): array
    {
        return $this->config[self::selectColumns];
    }

    public function updateColumns(): array
    {
        return $this->config[self::updateColumns];
    }

    public function whereIdEquals()
    {
        return implode(' AND ', array_map(function ($col) { return "{$col} = :{$col}"; }, $this->primaryKey()));
    }

    public function filter($params, string $columns): array
    {
        return array_filter(
            $params,
            function ($key) use ($columns) {
                return in_array($key, $this->config[$columns]);
            },
            ARRAY_FILTER_USE_KEY
        );
    }

    public function insertSql(): string
    {
        $columns = implode(', ', $this->insertColumns());
        $values = implode(', ', array_map(function ($col) { return ":{$col}"; }, $this->insertColumns()));

        return "INSERT INTO {$this->tableName()} ({$columns}) VALUES ({$values})";
    }

    public function selectSql($where = '1'): string
    {
        $columns = implode(', ', $this->selectColumns());

        return "SELECT {$columns} FROM {$this->tableName()} WHERE {$where}";
    }

    public function updateSql($params): string
    {
        $values = implode(', ', array_map(function ($col) { return "{$col} = :{$col}"; }, array_keys($params)));

        return "UPDATE {$this->tableName()} SET {$values} WHERE {$this->whereIdEquals()}";
    }

    public function deleteSql(): string
    {
        return "DELETE FROM {$this->tableName()} WHERE {$this->whereIdEquals()}";
    }
}
