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
}
