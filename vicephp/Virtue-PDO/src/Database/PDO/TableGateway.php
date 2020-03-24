<?php

namespace Virtue\Database\PDO;

use Virtue\Database;

class TableGateway implements Database\TableGateway
{
    /** @var ExecutesStatements */
    private $conn;
    /** @var Database\Table */
    private $table;

    public function __construct(ExecutesStatements $conn, Database\Table $table)
    {
        $this->conn = $conn;
        $this->table = $table;
    }

    public function all($where = '1', array $params = []): array
    {
        return $this->conn->execute($this->table->selectSql($where), $params)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function byId(array $id): array
    {
        return $this->conn->execute($this->table->selectSql($this->table->whereIdEquals()), $id)->fetchAll(\PDO::FETCH_ASSOC)[0];
    }

    public function insert(array $params)
    {
        $params = $this->table->filter($params, Database\Table::insertColumns);

        $this->conn->execute($this->table->insertSql(), $params);
    }

    public function update(array $id, array $params)
    {
        $params = $this->table->filter($params, Database\Table::updateColumns);

        $this->conn->execute($this->table->updateSql($params), array_merge($id, $params));
    }

    public function delete(array $id)
    {
        $id = $this->table->filter($id, Database\Table::primaryKey);

        $this->conn->execute($this->table->deleteSql(), $id);
    }
}
