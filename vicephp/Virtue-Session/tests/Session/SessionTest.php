<?php

namespace Virtue\Session;

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testStoresVariables()
    {
        $session = new Session();
        $this->assertEquals(false, $session->has('aVar'));

        $session->set('aVar', 'aVal');
        $this->assertEquals(true, $session->has('aVar'));
        $this->assertEquals($_SESSION['aVar'], 'aVal');
        $this->assertEquals('aVal', $session->get('aVar'));

        $session->unset('aVar');
        $this->assertEquals(false, $session->has('aVar'));
        $this->assertEquals(false, isset($_SESSION['aVar']));
        $this->assertEquals('aDefault', $session->get('aVar', 'aDefault'));
    }
}
