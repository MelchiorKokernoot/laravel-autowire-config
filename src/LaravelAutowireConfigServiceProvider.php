<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelAutowireConfigServiceProvider extends PackageServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        /** @var LaravelAutowireConfig $autoWirer */
        $autoWirer = $this->app->get(LaravelAutowireConfig::class);
        $this->app->afterResolving($autoWirer->getAfterResolvingClosure());
    }

    public function configurePackage(Package $package): void
    {
        $package->name('laravel-autowire-config');
    }
}
