<?php

namespace Virtue\Access;

class LoginTest extends \PHPUnit\Framework\TestCase
{
    public function testGetUsername()
    {
        $this->assertEquals('aUser', (new Login([Login::Username => 'aUser', Login::Password => 'aPassword']))->getUsername());
    }

    public function testAsArray()
    {
        $params = [Login::Username => 'aUser', Login::Password => 'aUser', 'aParam' => 'aValue'];
        $this->assertEquals($params, (new Login($params))->asArray());
    }
}
