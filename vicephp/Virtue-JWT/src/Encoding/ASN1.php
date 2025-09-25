<?php

namespace Virtue\Encoding;

class ASN1
{
    public const INTEGER = 2;
    public const BIT_STRING = 3;
    public const OCTET_STRING = 4;
    public const OBJECT_IDENTIFIER = 6;
    public const NULL = 5;
    public const SEQUENCE = 48;

    /** @var int */
    private $type;

    /** @var string */
    private $bytes;

    /** @var string */
    private $rest;

    private function __construct(int $type, string $bytes, string $rest = "")
    {
        $this->type = $type;
        $this->bytes = $bytes;
        $this->rest = $rest;
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

    public static function decode(string $string): self
    {
        $offset = 0;
        assert(strlen($string) > 1);
        $type = ord($string[$offset++]);

        $length = ord($string[$offset++]);
        if ($length & 0x80) {
            $temp = $length & ~0x80;
            $result = \unpack("N", str_pad(substr($string, $offset, $temp), 4, "\00", STR_PAD_LEFT));
            assert($result !== false);
            assert(count($result) == 1);
            $length = array_shift($result);
            assert(is_int($length));
            $offset += $temp;
        }

        $bytes = substr($string, $offset, $length);
        $rest = substr($string, $offset + $length);

        return new self($type, $bytes, $rest);
    }

    public function bytes(): string
    {
        return $this->bytes;
    }

    public function type(): int
    {
        return $this->type;
    }

    public function rest(): string
    {
        return $this->rest;
    }

    public function length(): int
    {
        return strlen($this->bytes);
    }
}
