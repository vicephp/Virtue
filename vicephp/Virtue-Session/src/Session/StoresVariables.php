<?php

namespace Virtue\Session;

interface StoresVariables
{
    public function has(string $name): bool;
    public function get(string $name, $default = null);
    public function set(string $name, $value): void;
    public function unset(string $name): void;
}
