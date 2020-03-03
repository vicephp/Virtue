<?php

namespace Virtue\Database;

interface TableGateway
{
    public function all($where = '1', array $params = []): array;
    public function byId(array $id);
    public function insert(array $params);
    public function update(array $id, array $params);
    public function delete(array $id);
}
