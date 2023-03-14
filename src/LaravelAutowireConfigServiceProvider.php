<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig;

use Illuminate\Support\ServiceProvider;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use MelchiorKokernoot\LaravelAutowireConfig\Events\AfterAutowiring;
use MelchiorKokernoot\LaravelAutowireConfig\Events\BeforeAutowiring;
use MelchiorKokernoot\LaravelAutowireConfig\Events\RegisteredAutowiringCallback;
use MelchiorKokernoot\LaravelAutowireConfig\Strategies\PropNameStrategy;
use ReflectionClass;
use function assert;
use function config;
use function config_path;

class LaravelAutowireConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/autowire-configs.php', 'autowire-config');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                    __DIR__ . '/../config/autowire-configs.php' => config_path('autowire-configs.php'),
                ],
                'autowire-configs',
            );
        }

        //Stop the registration of the resolving callback if the application is already an instance of our
        //custom Application. although this should work in combination, for sanitization and performance
        //reasons we do not want this to happen in other environments than when we are unit testing.
        if ($this->app instanceof Application && !$this->app->runningUnitTests()) {
            return;
        }

        RegisteredAutowiringCallback::dispatch();

        $this->app->resolving(
            AutowiresConfigs::class,
            static function (AutowiresConfigs $object, $app): void {
                $reflection = new ReflectionClass($object);
                $reflectionParameters = $reflection->getConstructor()?->getParameters();

                if (
                    $reflectionParameters === null ||
                    $reflectionParameters === [] ||
                    $reflection->getConstructor() === null
                ) {
                    return;
                }

                $autowiringStrategy = $app->get(config('autowire-configs.strategy', PropNameStrategy::class));

                BeforeAutowiring::dispatch($object);
                $autowiringStrategy->wire($object, $reflection);
                AfterAutowiring::dispatch($object);
            },
        );
    }
}
