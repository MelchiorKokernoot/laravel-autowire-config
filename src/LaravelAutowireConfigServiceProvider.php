<?php

namespace MelchiorKokernoot\LaravelAutowireConfig;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MelchiorKokernoot\LaravelAutowireConfig\Commands\LaravelAutowireConfigCommand;

class LaravelAutowireConfigServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-autowire-config')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-autowire-config_table')
            ->hasCommand(LaravelAutowireConfigCommand::class);
    }
}
