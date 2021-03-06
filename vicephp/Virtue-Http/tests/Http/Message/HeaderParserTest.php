<?php

namespace Virtue\Http\Message;

use GuzzleHttp\Psr7\ServerRequest as Request;
use PHPUnit\Framework\TestCase;

class HeaderParserTest extends TestCase
{
    public function testAccept()
    {
        $request = new Request('GET', '/get',
             [
            'Accept' => implode(', ', [
                'text/html',
                'application/xhtml+xml',
                'application/xml;q=0.9',
                '*/*;q=0.8',
                'application/signed-exchange;v=b3;q=0.9',
            ])
        ]);
        $parser = (new RequestParser)->header($request);

        $this->assertEquals('text/html', $parser->bestAccept(['text/html', 'application/xml'], $request));
        $this->assertEquals('text/html', $parser->bestAccept(['text/html', 'application/xhtml+xml'], $request));
        $this->assertEquals('klaatu/barada-nikto', $parser->bestAccept(['klaatu/barada-nikto'], $request));
    }

    public function testAcceptCharset()
    {
        $request = new Request('GET', '/get', [
            'Accept-Charset' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7'
        ]);
        $parser = (new RequestParser)->header($request);

        $this->assertEquals('ISO-8859-1', $parser->bestCharset(['ISO-8859-1', 'utf-8'], $request));
        $this->assertEquals('iso-8859-1', $parser->bestCharset(['iso-8859-1', 'utf-8'], $request));
        $this->assertEquals('utf-8', $parser->bestCharset(['utf-8'], $request));
        $this->assertEquals('klaatu-barada-nikto', $parser->bestCharset(['klaatu-barada-nikto'], $request));
    }

    public function testAcceptLanguage()
    {
        $request = new Request('GET', '/get', [
            'Accept-Language' => 'en-us,en;q=0.5'
        ]);
        $parser = (new RequestParser)->header($request);

        $this->assertEquals('en-us', $parser->bestLanguage(['en-us', 'en'], $request));
        $this->assertEquals('en', $parser->bestLanguage(['en'], $request));
    }

    public function testProxiesXForwardedFor()
    {
        $request = new Request('GET', '/get', [
            'X-Forwarded-For' => '203.0.113.195, 70.41.3.18, 150.172.238.178'
        ]);
        $parser = (new RequestParser)->header($request);

        $this->assertEquals(['70.41.3.18', '150.172.238.178'], $parser->proxiesXForwardedFor());
    }
}
