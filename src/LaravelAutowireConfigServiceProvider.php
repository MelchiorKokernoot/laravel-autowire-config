<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig;

use Illuminate\Support\ServiceProvider;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use MelchiorKokernoot\LaravelAutowireConfig\Strategies\PropNameStrategy;
use ReflectionClass;
use Webmozart\Assert\Assert;

use function config;

class LaravelAutowireConfigServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/autowire-configs.php', 'autowire-configs');
    }

    public function boot(): void
    {
        $this->app->afterResolving(AutowiresConfigs::class, static function (object|string $object, $app): void {
            Assert::isInstanceOf($object, AutowiresConfigs::class);
            $reflection = new ReflectionClass($object);
            $reflectionParameters = $reflection->getConstructor()?->getParameters();

            if ($reflectionParameters === null || $reflectionParameters === []) {
                return;
            }

            $autowiringStrategy = $app->get(config('autowire-configs.strategy', PropNameStrategy::class));
            $autowiringStrategy->wire($object, $reflection);
        });
    }
}
