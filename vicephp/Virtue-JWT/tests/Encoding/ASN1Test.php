<?php

namespace Virtue\Encoding;

use PHPUnit\Framework\TestCase;

class ASN1Test extends TestCase
{
    public function testInteger(): void
    {
        $asn1 = ASN1::int(\strrev(\pack('s', 1337)));
        $this->assertEquals("AgIFOQ==", $asn1->__toString());
        $asn1 = ASN1::int(\strrev(\pack('s', -1337)));
        $this->assertEquals("AgL6xw==", $asn1->__toString());
    }

    public function testUnsignedInteger(): void
    {
        $asn1 = ASN1::uint(\strrev(\pack('s', 1337)));
        $this->assertEquals("AgIFOQ==", $asn1->__toString());
        $asn1 = ASN1::uint(\strrev(\pack('s', -1337)));
        $this->assertEquals("AgMA+sc=", $asn1->__toString());
    }

    public function testOID(): void
    {
        $asn1 = ASN1::oid("1.2.840.113549.1.1.1");
        $this->assertEquals("BgkqhkiG9w0BAQE=", $asn1->__toString());
    }

    public function testSequence(): void
    {
        $asn1 = ASN1::seq(
            ASN1::int(\strrev(\pack('s', 1337))),
            ASN1::oid("1.2.840.10045.2.1"),
        );
        $this->assertEquals("MA0CAgU5BgcqhkjOPQIB", $asn1->__toString());
    }

    public function testBitString(): void
    {
        $asn1 = ASN1::bitstr("Hello World");
        $this->assertEquals("AwwASGVsbG8gV29ybGQ=", $asn1->__toString());
    }

    public function testOctetString(): void
    {
        $asn1 = ASN1::octstr("Hello World");
        $this->assertEquals("BAtIZWxsbyBXb3JsZA==", $asn1->__toString());
    }

    public function testDecode(): void
    {
        $longString = "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
                      "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
                      "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
                      "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
                      "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
                      "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
                      "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" .
                      "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA";
        $asn1 = ASN1::seq(
            ASN1::int(\strrev(\pack('s', 1337))),
            ASN1::bitstr($longString),
        );

        $block = ASN1::decode($asn1->encode());
        $this->assertEquals(ASN1::SEQUENCE, $block->type());
        $this->assertEquals(521, $block->length());
        $this->assertEmpty($block->rest());

        $block = ASN1::decode($block->bytes());
        $this->assertEquals(ASN1::INTEGER, $block->type());
        $this->assertEquals(2, $block->length());
        $this->assertEquals(\strrev(\pack('s', 1337)), $block->bytes());
        $this->assertNotEmpty($block->rest());

        $block = ASN1::decode($block->rest());
        $this->assertEquals(ASN1::BIT_STRING, $block->type());
        $this->assertEquals(strlen($longString) + 1, $block->length());
        $this->assertEquals("\00" . $longString, $block->bytes());
        $this->assertEmpty($block->rest());

    }

}
