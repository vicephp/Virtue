<?php

namespace Virtue\Encoding;

class ASN1
{
    private const INTEGER = 2;
    private const BIT_STRING = 3;
    private const OCTET_STRING = 4;
    private const OBJECT_IDENTIFIER = 6;
    private const NULL = 5;
    private const SEQUENCE = 48;


    /** @var int */
    private $type;

    /** @var string */
    private $bytes;

    private function __construct(int $type, string $bytes)
    {
        $this->type = $type;
        $this->bytes = $bytes;
    }

    public function encode(): string
    {
        return  \pack('Ca*a*', $this->type, $this->encodeLength(strlen($this->bytes)), $this->bytes);
    }

    public function __toString(): string
    {
        return \base64_encode($this->encode());
    }

    private function encodeLength(int $length): string
    {
        if ($length <= 0x7F) {
            return \chr($length);
        }

        $temp = ltrim(\pack('N', $length), "\x00");

        return \pack('Ca*', 0x80 | \strlen($temp), $temp);
    }

    public static function int(string $value): self
    {
        return new self(self::INTEGER, $value);
    }

    public static function uint(string $value): self
    {
        return (ord($value[0]) & 0x80) === 0 ? self::int($value) : self::int("\00" . $value);
    }

    public static function oid(string $value): self
    {
        $values = explode('.', $value);
        assert(count($values) >= 2);
        $firstComp = array_shift($values);
        assert(is_numeric($firstComp));
        $comp = array_shift($values);
        assert(is_numeric($comp));
        $out = chr(intval($firstComp) * 40 + intval($comp));

        while (($comp = array_shift($values)) !== null) {
            assert(is_numeric($comp));
            $comp = intval($comp);
            $v = '';
            do {
                $v .= chr($comp & 127 | ($v == '' ? 0 : (1 << 7)));
                $comp >>= 7;
            } while ($comp > 0);
            $out .= strrev($v);
        }

        return new self(self::OBJECT_IDENTIFIER, $out);
    }

    public static function seq(self ...$values): self
    {
        return new self(self::SEQUENCE, implode('', array_map(function (self $value): string {
            return $value->encode();
        }, $values)));
    }

    public static function bitstr(string $string): self
    {
        return new self(self::BIT_STRING, "\00" . $string);
    }

    public static function octstr(string $string): self
    {
        return new self(self::OCTET_STRING, $string);
    }

    public static function null(): self
    {
        return new self(self::NULL, "");
    }
}
