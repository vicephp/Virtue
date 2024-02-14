<?php

namespace Virtue\Config;

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /** @test */
    public function configFromArray()
    {
        $config = Config::fromArray(['key' => 'value']);
        $this->assertEquals('value', $config->get('key'));
    }

    /** @test */
    public function fallbackToDefault()
    {
        $config = Config::fromArray(['key' => 'value']);
        $this->assertEquals('default', $config->get('missing', 'default'));
        $this->assertNull($config->get('missing'));
    }

    /** @test */
    public function getByPath()
    {
        $config = Config::fromArray(['key' => ['subkey' => 'value']]);
        $this->assertEquals('value', $config->get('key/subkey'));
    }

    /** @test */
    public function mergeConfigs()
    {
        $config = Config::fromArray(['key' => 'value'])
            ->merge(Config::fromArray(['key' => 'new value']));
        $this->assertEquals('new value', $config->get('key'));
    }

    /** @test */
    public function configFromEnv()
    {
        $config = Config::fromEnv([
            'VIRTUE_CONFIG' => '{"key":"value"}',
            'VIRTUE_CONFIG_FOO_BAR' => 'baz',
        ]);

        $this->assertEquals('value', $config->get('key'));
        $this->assertEquals('baz', $config->get('foo/bar'));
    }

    /** @test */
    public function providesArrayAccess()
    {
        $config = Config::fromArray(['key' => 'value']);
        $this->assertEquals('value', $config['key']);
        $this->assertTrue(isset($config['key']));
        $this->assertFalse(isset($config['missing']));
    }
}
