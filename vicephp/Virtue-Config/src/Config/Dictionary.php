<?php

namespace Virtue\Config;

interface Dictionary
{
    public function get(string $key, $default = null);
}
