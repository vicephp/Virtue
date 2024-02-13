<?php

namespace Virtue\Config;

class Config implements \ArrayAccess, Dictionary
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
        return self::fromArray(
            array_reduce(
                array_filter(array_keys($env), function (string $key) use ($prefix) {
                    return strpos($key, "{$prefix}_") === 0;
                }),
                function (array $config, string $key) use ($env, $prefix) {
                    return array_replace_recursive($config, array_reduce(
                        array_reverse(explode('_', strtolower(substr($key, strlen("{$prefix}_"))))),
                        function ($value, $key) {
                            return [$key => $value];
                        },
                        json_decode($env[$key], true) ?: $env[$key]
                    ));
                },
                json_decode($env[$prefix] ?? null, true) ?: ($env[$prefix] ?? [])
            ),
        );
    }

    public function get(string $path, $default = null)
    {
        return array_reduce(
            explode('/', $path),
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
        return $this->get($offset) !== null;
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException(__METHOD__ . ' not allowed');
    }

    public function offsetUnset($offset)
    {
        throw new \RuntimeException(__METHOD__ . ' not allowed');
    }
}
