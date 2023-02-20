<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig;


use Illuminate\Support\ServiceProvider;

class LaravelAutowireConfigServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        /** @var LaravelAutowireConfig $autoWirer */
        $autoWirer = $this->app->get(LaravelAutowireConfig::class);
        $this->app->afterResolving($autoWirer->getAfterResolvingClosure());
    }
}
