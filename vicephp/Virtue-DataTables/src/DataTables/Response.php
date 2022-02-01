<?php

namespace Virtue\DataTables;

class Response implements \JsonSerializable
{
    const draw = 'draw';
    const recordsTotal = 'recordsTotal';
    const recordsFiltered = 'recordsFiltered';
    const data = 'data';
    const error = 'error';

    /** @var array */
    private $result = [
        self::draw => 1,
        self::recordsTotal => 0,
        self::recordsFiltered => 0,
        self::data => [],
    ];

    /**
     * @param array $result
     */
    public function __construct(array $result = [])
    {
        $this->result = array_replace($this->result, $result);
    }

    /**
     * @param string $message
     * @return Response
     */
    public static function withError(string $message): self
    {
        return new self([self::error => $message]);
    }

    public function jsonSerialize(): array
    {
        return $this->result;
    }
}
