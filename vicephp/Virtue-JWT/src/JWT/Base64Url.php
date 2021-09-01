<?php

namespace Virtue\JWT;

class Base64Url
{
    public static function encode(string $input): string
    {
        return \str_replace('=', '', \strtr(\base64_encode($input), '+/', '-_'));
    }

    public static function decode(string $input): string
    {
        $input .= \str_repeat('=', 4 - \strlen($input) % 4);

        return \base64_decode(\strtr($input, '-_', '+/'));
    }
}
