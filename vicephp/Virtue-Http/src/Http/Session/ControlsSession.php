<?php

namespace Virtue\Http\Session;

interface ControlsSession
{
    public function start($options = []): bool;
    public function destroy(): bool;
    public function reset(): bool;
    public function status(): int;
}
