<?php

namespace Virtue\Http\Message\Header;

use Psr\Http\Message\ServerRequestInterface as ServerRequest;

class HeaderParser
{
    const IPV4 = FILTER_FLAG_IPV4;
    const IPV6 = FILTER_FLAG_IPV6;
    /** @var AcceptParser */
    private $acceptHeader;
    /** @var ForwardedParser */
    private $forwarded;
    /** @var ServerRequest */
    private $request;

    public function __construct(AcceptParser $acceptHeader, ForwardedParser $forwarded, ServerRequest $request)
    {
        $this->acceptHeader = $acceptHeader;
        $this->forwarded = $forwarded;
        $this->request = $request;
    }

    public function bestCharset(array $supported, $default = ''): string
    {
        return $this->bestMatch('Accept-Charset', $supported, $default);
    }

    public function bestEncoding(array $supported, $default = ''): string
    {
        return $this->bestMatch('Accept-Encoding', $supported, $default);
    }

    public function bestLanguage(array $supported, $default = ''): string
    {
        return $this->bestMatch('Accept-Language', $supported, $default);
    }

    public function bestAccept(array $supported, $default = ''): string
    {
        return $this->bestMatch('Accept', $supported, $default);
    }

    public function bestMatch(string $header, array $supported, $default = ''): string
    {
        if ($this->request->hasHeader($header)) {
            $line = implode(',', $this->request->getHeader($header));
            return $this->acceptHeader->bestMatch($supported, $line);
        }

        return $default;
    }

    public function bestClientIp($version = self::IPV4): string
    {
        if ($this->request->hasHeader('X-Forwarded-For')) {
            $byVersion = function($ip) use ($version) {
                return filter_var($ip, FILTER_VALIDATE_IP, $version);
            };
            $line = implode(',', $this->request->getHeader('X-Forwarded-For'));

            return array_filter($this->forwarded->parseXForwardedFor($line), $byVersion)[0];
        }

        return $this->request->getServerParams()['REMOTE_ADDR'];
    }

    public function proxiesXForwardedFor($version = self::IPV4): array
    {
        if ($this->request->hasHeader('X-Forwarded-For')) {
            $byVersion = function($ip) use ($version) {
                return filter_var($ip, FILTER_VALIDATE_IP, $version);
            };
            $line = implode(',', $this->request->getHeader('X-Forwarded-For'));
            return array_slice(
                array_filter($this->forwarded->parseXForwardedFor($line), $byVersion),
                1
            );
        }

        return [];
    }
}
