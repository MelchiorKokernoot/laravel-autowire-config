<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use TypeError;
use function config;

class ConfigTest extends TestCase
{

    public function testItThrowsAnExceptionWhenArrayIsNotArray(): void
    {
        config()->set(['foo' => 'bar']);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type array and got: string');

        Config::array('foo');
    }

    public function testItDoesNotThrowAnExceptionWhenArrayIsArray(): void
    {
        config()->set(['foo' => ['bar']]);
        $this->assertIsArray(Config::array('foo'));
    }

    public function testItThrowsAnExceptionWhenNotNullOrArrayIsString(): void
    {
        config()->set(['foo' => 'bar']);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type arrayOrNull and got: string');

        Config::arrayOrNull('foo');
    }

    public function testItThrowsAnErrorWhenStringIsNotString()
    {
        config()->set(['foo' => 1]);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type string and got: integer');

        Config::string('foo');
    }

    public function testItDoesNotThrowAnExceptionWhenStringIsString()
    {
        config()->set(['foo' => 'bar']);
        $this->assertIsString(Config::string('foo'));
    }

    public function testItThrowsAnExceptionWhenStringOrNullIsNotStringOrNull(): void
    {
        config()->set(['foo' => 1]);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type stringOrNull and got: integer');

        Config::stringOrNull('foo');
    }

    public function testItDoesNotThrowAnExceptionWhenStringOrNullIsStringOrNull()
    {
        config()->set(['foo' => 'bar']);
        $this->assertIsString(Config::stringOrNull('foo'));

        config()->set(['foo' => null]);
        $this->assertNull(Config::stringOrNull('foo'));
    }

    public function testItThrowsAnExceptionWhenIntegerOrNullIsNotIntegerOrNull()
    {
        config()->set(['foo' => 'bar']);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type integerOrNull and got: string');

        Config::integerOrNull('foo');
    }

    public function testItDoesNotThrowAnExceptionWhenIntegerOrNullIsIntegerOrNull()
    {
        config()->set(['foo' => 1]);
        $this->assertIsInt(Config::integerOrNull('foo'));

        config()->set(['foo' => null]);
        $this->assertNull(Config::integerOrNull('foo'));
    }

    public function testItThrowsAnExceptionWhenIntegerIsNotInteger()
    {
        config()->set(['foo' => 'bar']);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type integer and got: string');

        Config::integer('foo');
    }

    public function testItDoesNotThrowAnExceptionWhenIntegerIsInteger()
    {
        config()->set(['foo' => 1]);
        Config::integer('foo');
        $this->assertIsInt(Config::integer('foo'));
    }

    public function testItThrowsAnExceptionWhenBooleanIsNotBoolean()
    {
        config()->set(['foo' => 'bar']);
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type boolean and got: string');

        Config::boolean('foo');
    }

    public function testItDoesNotThrowExceptionWhenBooleanIsBoolean()
    {
        config()->set(['foo' => true]);
        $this->assertTrue(Config::boolean('foo'));
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
