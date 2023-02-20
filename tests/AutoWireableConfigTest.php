<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\ArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\BooleanConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\IntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableIntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableStringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\StringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use RuntimeException;
use Webmozart\Assert\InvalidArgumentException;
use function app;
use function config;

class AutoWireableConfigTest extends TestCase
{
    public function testItInjectsTheConfigBasedOnConstructorArgumentNames(): void
    {
        config()->set([
            'app.name' => 'nice-app',
            'foo.bar' => 'baz',
            'test.integer' => 1,
            'test.boolean' => true,
        ]);

        $dummy = app(DummyClass::class);
        $this->assertEquals('nice-app', $dummy->getAppName());
        $this->assertEquals('baz', $dummy->fooBar);
        $this->assertEquals(1, $dummy->testInteger->value());
        $this->assertEquals(true, $dummy->testBoolean->value());
    }

    public function testItReturnsAnArrayWhenUsingArrayConfigTypeHint(): void
    {
        config()->set([
            'foo.array' => [
                'foo' => 'bar',
            ],
        ]);

        $dummy = app(DummyClass::class);
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->value());
    }

    public function testNullableArray()
    {
        config()->set([
            'foo.array' => null
        ]);

        $dummy = app(NullableDummyClass::class);
        $this->assertNull($dummy->fooArray->value());
    }

    public function testShorthandValueGetter()
    {
        config()->set([
            'foo.array' => [
                'foo' => 'bar',
            ],
        ]);

        $dummy = app(DummyClass::class);
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->v());
    }

    public function testItInjectsRegularDependenciesCorrectly(): void
    {
        $dummy = app(DummyClass::class);
        $this->assertInstanceOf(DummyDependency::class, $dummy->dummyDependency);
    }

    public function testItThrowsAnExceptionWhenStringConfigTypeIsNull(): void
    {
        config()->set([
            'foo.bar' => null,
            'test.integer' => 1,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a string. Got: NULL');
        $dummy = app(DummyClass::class);

        $this->assertEquals('', (string)$dummy->fooBar);
        $this->assertEquals(1, $dummy->testInteger->value());
        $this->assertEquals('1', (string)$dummy->testInteger);
    }

    public function testItThrowsARuntimeExceptionWhenTryingToCastArrayToString(): void
    {
        config()->set([
            'foo.array' => [
                'foo' => 'bar',
            ],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot convert value to string');
        $dummy = app(DummyClass::class);
        $this->assertEquals('', $dummy->fooArray);
    }

    public function testItDoesNotThrowAnExceptionWhenAccessingTheValueUnwrapperOnArray()
    {
        config()->set([
            'foo.array' => [
                'foo' => 'bar',
            ],
        ]);

        $dummy = app(DummyClass::class);
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->value());
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->v());
    }

    public function testItDoesNotThrowAnExceptionWhenNullableStringConfigTypeIsNull(): void
    {
        config()->set([
            'foo.string' => null,
            'foo.array' => null,
            'foo.int' => null,
        ]);

        $dummy = app(NullableDummyClass::class);
        $this->assertEquals('', (string)$dummy->fooString);
        $this->assertEquals('', (string)$dummy->fooArray);
        $this->assertEquals('', (string)$dummy->fooInt);
    }

    public function testItSkipsUnTypedArguments(): void
    {
        config()->set([
            'foo.bar' => 'baz',
        ]);

        $dummy = app(NonTypedDummyClass::class);
        $this->assertEquals('default', $dummy->fooBar);
    }

    public function testItSkipsClassesWithNoConstructorArguments()
    {
        $dummy = app(DependencyWithNoConstructorArguments::class);
        $this->assertInstanceOf(DependencyWithNoConstructorArguments::class, $dummy);
    }
}

// phpcs:ignore
class DummyClass implements AutowiresConfigs
{
    public function __construct(
        private StringConfig   $appName,
        public StringConfig    $fooBar,
        public IntegerConfig   $testInteger,
        public ArrayConfig     $fooArray,
        public BooleanConfig   $testBoolean,
        public DummyDependency $dummyDependency,
    )
    {
    }

    public function getAppName(): string
    {
        return $this->appName->value();
    }
}

// phpcs:ignore
class NullableDummyClass implements AutowiresConfigs
{
    public function __construct(
        public NullableStringConfig  $fooString,
        public NullableArrayConfig   $fooArray,
        public NullableIntegerConfig $fooInt,
    )
    {
    }
}

// phpcs:ignore
class NonTypedDummyClass implements AutowiresConfigs
{
    public function __construct(
        public $fooBar = 'default',
    )
    {
    }
}

// phpcs:ignore
class DependencyWithNoConstructorArguments implements AutowiresConfigs
{
    public function __construct()
    {
    }
}

// phpcs:ignore
class DummyDependency
{
}
