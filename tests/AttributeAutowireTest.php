<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use Illuminate\Support\Facades\Event;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\StringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use MelchiorKokernoot\LaravelAutowireConfig\Events\AfterAutowiring;
use MelchiorKokernoot\LaravelAutowireConfig\Events\BeforeAutowiring;
use MelchiorKokernoot\LaravelAutowireConfig\Strategies\AttributeStrategy;
use MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures\DependencyWithNoConstructorArguments;
use MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures\DummyClassAttributes;
use MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures\DummyDependency;
use MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures\NonTypedDummyClass;
use MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures\NullableDummyClass;
use RuntimeException;
use TypeError;

use function app;
use function config;

class AttributeAutowireTest extends TestCase
{
    public function testItInjectsTheConfigBasedOnConstructorArgumentAttributes(): void
    {
        config()->set(
            [
                'app.name' => 'nice-app',
                'foo.bar' => 'baz',
                'test.integer' => 1,
                'test.boolean' => true,
            ],
        );

        $dummy = app(DummyClassAttributes::class);
        $this->assertEquals('nice-app', $dummy->getAppName());
        $this->assertEquals('baz', $dummy->fooBar);
        $this->assertEquals(1, $dummy->testInteger->value());
        $this->assertEquals(true, $dummy->testBoolean->value());
    }

    public function testItReturnsAnArrayWhenUsingArrayConfigTypeHint(): void
    {
        config()->set(
            [
                'foo.array' => [
                    'foo' => 'bar',
                ],
            ],
        );

        $dummy = app(DummyClassAttributes::class);
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->value());
    }

    public function testNullableArray(): void
    {
        config()->set(
            [
                'foo.array' => null,
            ],
        );

        $dummy = app(NullableDummyClass::class);
        $this->assertNull($dummy->fooArray->value());
    }

    public function testShorthandValueGetter(): void
    {
        config()->set(
            [
                'foo.array' => [
                    'foo' => 'bar',
                ],
            ],
        );

        $dummy = app(DummyClassAttributes::class);
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->v());
    }

    public function testItInjectsRegularDependenciesCorrectly(): void
    {
        $dummy = app(DummyClassAttributes::class);
        $this->assertInstanceOf(DummyDependency::class, $dummy->dummyDependency);
    }

    public function testItThrowsAnExceptionWhenStringConfigTypeIsNull(): void
    {
        config()->set(
            [
                'foo.bar' => null,
                'test.integer' => 1,
            ],
        );

        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Expected config value of type string and got: NULL');
        $dummy = app(DummyClassAttributes::class);

        $this->assertEquals('', (string) $dummy->fooBar);
        $this->assertEquals(1, $dummy->testInteger->value());
        $this->assertEquals('1', (string) $dummy->testInteger);
    }

    public function testItThrowsARuntimeExceptionWhenTryingToCastArrayToString(): void
    {
        config()->set(
            [
                'foo.array' => [
                    'foo' => 'bar',
                ],
            ],
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot convert value to string');
        $dummy = app(DummyClassAttributes::class);
        $this->assertEquals('', $dummy->fooArray);
    }

    public function testItDoesNotThrowAnExceptionWhenAccessingTheValueUnwrapperOnArray(): void
    {
        config()->set(
            [
                'foo.array' => [
                    'foo' => 'bar',
                ],
            ],
        );

        $dummy = app(DummyClassAttributes::class);
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->value());
        $this->assertEquals(['foo' => 'bar'], $dummy->fooArray->v());
    }

    public function testItDoesNotThrowAnExceptionWhenNullableStringConfigTypeIsNull(): void
    {
        config()->set(
            [
                'foo.string' => null,
                'foo.array' => null,
                'foo.int' => null,
            ],
        );

        $dummy = app(NullableDummyClass::class);
        $this->assertEquals('', (string) $dummy->fooString);
        $this->assertEquals('', (string) $dummy->fooArray);
        $this->assertEquals('', (string) $dummy->fooInt);
    }

    public function testItThrowsAnExceptionWithMultipleAttributeArgs(): void
    {
        config()->set(
            [
                'foo.bar' => 'baz',
            ],
        );

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('ConfigValueWrapper attribute must have exactly one argument');

        app(DummyClassMultipleArgsAttribute::class);
    }

    public function testItSkipsUnTypedArguments(): void
    {
        config()->set(
            [
                'foo.bar' => 'baz',
            ],
        );

        $dummy = app(NonTypedDummyClass::class);
        $this->assertEquals('default', $dummy->fooBar);
    }

    public function testItSkipsClassesWithNoConstructorArguments(): void
    {
        $dummy = app(DependencyWithNoConstructorArguments::class);
        $this->assertInstanceOf(DependencyWithNoConstructorArguments::class, $dummy);
    }

    public function testItDoesRegisterResolvingCallbackInsideTest(): void
    {
        $this->app->bind('env', static fn() => 'testing');
        config()->set(['foo.bar' => 'bar']);
        Event::fake();

        app()->make(DummyClassAttributes::class);

        Event::assertDispatched(BeforeAutowiring::class);
        Event::assertDispatched(AfterAutowiring::class);
    }

    protected function setUp(): void
    {
        parent::setUp();

        config()->set(
            [
                'autowire-configs.strategy' => AttributeStrategy::class,
            ],
        );
    }
}

//phpcs:ignore
class DummyClassMultipleArgsAttribute implements AutowiresConfigs{
    public function __construct(
        #[StringConfig('foo.bar', 'baz')]
        public StringConfig $thisCanBeAnythingNow,
    ) {
    }
}
