<?php

namespace Virtue\Http\Message\Header;

use PHPUnit\Framework\TestCase;

class AcceptParserTest extends TestCase
{
    public function testAccept()
    {
        $accept = implode(', ', [
            'text/html',
            'application/xhtml+xml',
            'application/xml;q=0.9',
            '*/*;q=0.8',
            'application/signed-exchange;v=b3;q=0.9',
        ]);
        $parser = new AcceptParser();

        $this->assertEquals('text/html', $parser->bestMatch(['text/html', 'application/xml'], $accept));
        $this->assertEquals('text/html', $parser->bestMatch(['text/html', 'application/xhtml+xml'], $accept));
        $this->assertEquals('klaatu/barada', $parser->bestMatch(['klaatu/barada'], $accept));
    }

    public function testAcceptCharset()
    {
        $accept ='ISO-8859-1,utf-8;q=0.7,*;q=0.7';
        $parser = new AcceptParser();

        $this->assertEquals('ISO-8859-1', $parser->bestMatch(['ISO-8859-1', 'utf-8'], $accept));
        $this->assertEquals('iso-8859-1', $parser->bestMatch(['iso-8859-1', 'utf-8'], $accept));
        $this->assertEquals('utf-8', $parser->bestMatch(['utf-8'], $accept));
        $this->assertEquals('klaatu-barada-nikto', $parser->bestMatch(['klaatu-barada-nikto'], $accept));
    }

    public function testAcceptLanguage()
    {
        $accept = 'en-us,en;q=0.5';
        $parser = new AcceptParser();

        $this->assertEquals('en-us', $parser->bestMatch(['en-us', 'en'], $accept));
        $this->assertEquals('en', $parser->bestMatch(['en'], $accept));
    }
}
