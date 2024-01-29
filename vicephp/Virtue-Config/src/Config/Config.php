<?php

namespace Virtue\Config;

class Config implements \ArrayAccess
{
    private $values = [];

    public static function fromArray(array $array): self
    {
        $config = new self();
        $config->values = $array;
        return $config;
    }

    public static function fromFile(string $filename): self
    {
        return self::fromArray(include $filename);
    }

    public static function fromEnv(array $env = null, string $prefix = 'VIRTUE_CONFIG')
    {
        $env = $env ?: $_ENV;
        return self::fromArray(array_reduce(
            array_filter(array_keys($env), fn(string $key) => strpos($key, "{$prefix}_") === 0),
            fn(array $config, string $key) => array_replace_recursive($config, array_reduce(
                array_reverse(explode('_', strtolower(substr($key, strlen("{$prefix}_"))))),
                fn($value, $key) => [$key => $value],
                json_decode($env[$key], true) ?: $env[$key]
            )),
            json_decode($env[$prefix] ?? null, true) ?: ($env[$prefix] ?? [])
        ));
    }

    public function get(string $path, $default = null)
    {
        return array_reduce(
            explode('.', $path),
            function ($value, $key) {
                return is_array($value) ? $value[$key] ?? null : $value;
            },
            $this->values
        ) ?? $default;
    }

    /**
     * @param array|self $other
     * @return self
     */
    public function merge($other): self
    {
        if (is_array($other)) {
            $other = self::fromArray($other);
        }

        return self::fromArray(array_replace_recursive($this->values, $other->values));
    }

    public function offsetExists($offset)
    {
        return false;
    }

    public function offsetGet(mixed $offset)
    {
        return null;
    }

    public function offsetSet($offset, $value)
    {
    }

    public function offsetUnset($offset)
    {
    }
}
