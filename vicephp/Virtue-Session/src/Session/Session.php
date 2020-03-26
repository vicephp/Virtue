<?php

namespace Virtue\Session;

class Session implements ControlsSession, StoresVariables
{
    public function has(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    public function get(string $name, $default = null)
    {
        return $_SESSION[$name] ?? $default;
    }

    public function set(string $name, $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function unset(string $name): void
    {
        unset($_SESSION[$name]);
    }

    public function start($options = []): bool
    {
        return session_start($options);
    }

    public function destroy(): bool
    {
        return session_destroy();
    }

    public function reset(): bool
    {
        return session_reset();
    }

    public function status(): int
    {
        return session_status();
    }
}
