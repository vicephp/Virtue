<?php

namespace Virtue\Database\PDO;

class Server
{
    /** @var array */
    private $config = ['options' => [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]];

    public function __construct(array $config = [])
    {
        $this->config = array_replace_recursive($this->config, $config);
    }

    public function connect(): \PDO
    {
        return new \PDO(
            $this->config['dsn'],
            $this->config['username'],
            $this->config['password'],
            $this->config['options']
        );
    }
}
