<?php

namespace Virtue\Http\Message\Header;

use PHPUnit\Framework\TestCase;

class ForwardedParserTest extends TestCase
{
    public function testParseForwarded()
    {
        $forwarded = new ForwardedParser();

        $this->assertEquals(
            [['for' => '192.0.2.60', 'proto' => 'http', 'by' => '203.0.113.43',]],
            $forwarded->parseForwarded('for=192.0.2.60;proto=http;by=203.0.113.43')
        );
        $this->assertEquals(
            [['for' => '192.0.2.43'], ['for' => '198.51.100.17']],
            $forwarded->parseForwarded('for=192.0.2.43, for=198.51.100.17')
        );
        $this->assertEquals(
            [['for' => '192.0.2.43'], ['for' => '2001:db8:cafe::17']],
            $forwarded->parseForwarded('for=192.0.2.43, for="[2001:db8:cafe::17]"')
        );
    }

    public function testParseXForwarded()
    {
        $xForwarded = new ForwardedParser();

        $this->assertEquals(
            ['2001:db8:85a3:8d3:1319:8a2e:370:7348'],
            $xForwarded->parseXForwardedFor('2001:db8:85a3:8d3:1319:8a2e:370:7348')
        );
        $this->assertEquals(
            ['203.0.113.195'],
            $xForwarded->parseXForwardedFor('203.0.113.195')
        );
        $this->assertEquals(
            ['203.0.113.195', '70.41.3.18', '150.172.238.178'],
            $xForwarded->parseXForwardedFor('203.0.113.195, 70.41.3.18, 150.172.238.178')
        );
        $this->assertEquals(
            ['192.0.2.43', '2001:db8:cafe::17'],
            $xForwarded->parseXForwardedFor('192.0.2.43, 2001:db8:cafe::17')
        );
    }
}
