<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use AssertionError;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;

use function config;

class ConfigTest extends TestCase
{
    public function testItThrowsAnExceptionWhenNotNullOrArrayIsString(): void
    {
        config()->set(['foo' => 'bar']);
        $this->expectException(AssertionError::class);

        Config::arrayOrNull('foo');
    }

    public function testItDoesNotThrowAnExceptionWhenNullOrArrayIsNull(): void
    {
        config()->set(['foo' => null]);
        $this->assertNull(Config::arrayOrNull('foo'));
    }

    public function testItDoesNotThrowAnExceptionWhenNullOrArrayIsArray(): void
    {
        config()->set(['foo' => ['bar' => 'baz']]);
        $this->assertSame(['bar' => 'baz'], Config::arrayOrNull('foo'));
    }
}
