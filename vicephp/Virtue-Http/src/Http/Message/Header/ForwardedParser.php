<?php

namespace Virtue\Http\Message\Header;

class ForwardedParser
{
    /**
     * Forwarded: by=<identifier>;for=<identifier>;host=<host>;proto=<http|https>
     *
     * @param string $line
     * @return array
     */
    public function parseForwarded(string $line)
    {
        return array_map(
            function (string $entry) {
                return $this->parse($entry);
            },
            explode(',', $line)
        );
    }

    private function parse(string $entry): array
    {
        $params = array_reduce(
            explode(';', $entry),
            function (array $params, string $pair) {
                $pair = explode('=', $pair);
                $params[trim($pair[0])] = trim($pair[1]);
                return $params;
            },
            []
        );
        $params['for'] = trim(preg_replace('/"\[(.*)]"/', '\1', $params['for']));

        return $params;
    }

    /**
     * X-Forwarded-For: <client>, <proxy1>, <proxy2>
     *
     * @param string $line
     * @return array
     */
    public function parseXForwardedFor(string $line)
    {
        return array_map(
            function ($ip) {
                return trim($ip);
            },
            explode(',', $line)
        );
    }
}
