<?php

namespace Virtue\Forms;

use PHPUnit\Framework\TestCase;

class SelectElementTest extends TestCase
{
    public function testJsonSerialize()
    {
        $select = new SelectElement(['name' => 'aSelectElement']);

        $this->assertEquals(['element' => 'select', 'attributes' => ['name' => 'aSelectElement'], 'options' => []], $select->jsonSerialize());
    }
}
