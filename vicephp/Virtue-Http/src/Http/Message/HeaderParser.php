<?php

namespace Virtue\Http\Message;

use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class HeaderParser
{
    const IPV4 = FILTER_FLAG_IPV4;
    const IPV6 = FILTER_FLAG_IPV6;
    /** @var Header\AcceptParser */
    private $acceptHeader;
    /** @var Header\ForwardedParser */
    private $forwarded;

    public function __construct()
    {
        $this->acceptHeader = new Header\AcceptParser();
        $this->forwarded = new Header\ForwardedParser();
    }

    public function bestCharset(array $supported, ServerRequest $message, $default = ''): string
    {
        return $this->bestMatch('Accept-Charset', $supported, $message, $default);
    }

    public function bestEncoding(array $supported, ServerRequest $message, $default = ''): string
    {
        return $this->bestMatch('Accept-Encoding', $supported, $message, $default);
    }

    public function bestLanguage(array $supported, ServerRequest $message, $default = ''): string
    {
        return $this->bestMatch('Accept-Language', $supported, $message, $default);
    }

    public function bestAccept(array $supported, ServerRequest $message, $default = ''): string
    {
        return $this->bestMatch('Accept', $supported, $message, $default);
    }

    public function bestMatch(string $header, array $supported, ServerRequest $message, $default = ''): string
    {
        if ($message->hasHeader($header)) {
            $line = implode(',', $message->getHeader($header));
            return $this->acceptHeader->bestMatch($supported, $line);
        }

        return $default;
    }

    public function bestClientIp(ServerRequest $message, $version = self::IPV4): string
    {
        if ($message->hasHeader('X-Forwarded-For')) {
            $byVersion = function($ip) use ($version) {
                return filter_var($ip, FILTER_VALIDATE_IP, $version);
            };
            $line = implode(',', $message->getHeader('X-Forwarded-For'));

            return array_filter($this->forwarded->parseXForwardedFor($line), $byVersion)[0];
        }

        return $message->getServerParams()['REMOTE_ADDR'];
    }

    public function proxiesXForwardedFor(ServerRequest $message, $version = self::IPV4): array
    {
        if ($message->hasHeader('X-Forwarded-For')) {
            $byVersion = function($ip) use ($version) {
                return filter_var($ip, FILTER_VALIDATE_IP, $version);
            };
            $line = implode(',', $message->getHeader('X-Forwarded-For'));
            return array_slice(
                array_filter($this->forwarded->parseXForwardedFor($line), $byVersion),
                1
            );
        }

        return [];
    }
}
