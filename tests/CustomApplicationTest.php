<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Event;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Events\AfterAutowiring;
use MelchiorKokernoot\LaravelAutowireConfig\Events\BeforeAutowiring;
use MelchiorKokernoot\LaravelAutowireConfig\Events\RegisteredAutowiringCallback;
use MelchiorKokernoot\LaravelAutowireConfig\LaravelAutowireConfigServiceProvider;
use RuntimeException;

use function app;
use function config;

class CustomApplicationTest extends CustomApplicationTestCase
{
    public function testItResolvesPrimitivesWithDefaultValue(): void
    {
        $this->app->bind('test', static function () {
            return new class {
                public function __construct(
                    public string $string = 'default',
                )
                {
                }
            };
        });

        $this->assertSame('default', $this->app->make('test')->string);
    }

    public function testItThrowsAnExceptionWhenPrimitiveHasNoDefaultAndAttributeIsNotConfigOrConfigValueWrapper(): void
    {
        $this->app->bind('test', FakeAttribute::class);
        $this->expectException(BindingResolutionException::class);
        $this->app->make('test');
    }

    public function testItSkipsWhenPrimitiveHasNoDefaultAndAttributeIsNotConfigOrConfigValueWrapperAndAttributeHasMultipleParams(): void
    {
        $this->app->bind('test', RealAttributeMultipleParams::class);
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("ConfigValueWrapper attribute must have exactly one argument");
        $this->app->make('test');
    }

    public function testItSetsTheValueWhenPrimitiveHasNoDefualtAndAttributeIsConfig(): void
    {
        config()->set('foo.bar', 'bar');
        config()->set('foo.bool', true);
        config()->set('foo.int', 1);
        config()->set('foo.float', 1.1);
        config()->set('foo.array', ['a']);

        $this->app->bind('test', ConfigAttribute::class);
        $this->assertSame('bar', $this->app->make('test')->string);
        $this->assertSame(true, $this->app->make('test')->bool);
        $this->assertSame(1, $this->app->make('test')->int);
        $this->assertSame(1.1, $this->app->make('test')->float);
        $this->assertSame(['a'], $this->app->make('test')->array);
    }

    public function testItDoesNotRegisterResolvingCallbacksOutsideOfTests(): void
    {
        config()->set(['env' => 'production']);

        Event::fake();

        app()->make(ConfigAttributeWithDefaults::class);

        Event::assertNotDispatched(BeforeAutowiring::class);
        Event::assertNotDispatched(AfterAutowiring::class);
    }

    public function testItDoesNotRegisterResolivin(): void
    {
        $this->app->bind('env', static fn() => 'production');
        $provider = $this->app->make(LaravelAutowireConfigServiceProvider::class, ['app' => $this->app]);

        Event::fake();
        $provider->boot();
        Event::assertNotDispatched(RegisteredAutowiringCallback::class);
    }
}

class FakeAttribute
{
    public function __construct(
        #[FooBarAttribute('')]
        public string $string,
    )
    {
    }
}

class RealAttributeMultipleParams
{
    public function __construct(
        #[Config('', '')]
        public string $string,
    )
    {
    }
}


class SinglePropWithCorrectAttribute
{
    public function __construct(
        #[Config('foo.bar')]
        public string $string,
    )
    {
    }
}

class ConfigAttribute
{
    public function __construct(
        #[Config('foo.bar')]
        public string $string,
        #[Config('foo.bool')]
        public bool $bool,
        #[Config('foo.int')]
        public int $int,
        #[Config('foo.float')]
        public float $float,
        #[Config('foo.array')]
        public array $array,
    )
    {
    }
}

class ConfigAttributeWithDefaults
{
    public function __construct(
        #[Config('foo.bar')]
        public string $string = 'default',
        #[Config('foo.bool')]
        public bool $bool = true,
        #[Config('foo.int')]
        public int $int = 1,
        #[Config('foo.float')]
        public float $float = 1.1,
        #[Config('foo.array')]
        public array $array = ['a'],
    )
    {
    }
}
