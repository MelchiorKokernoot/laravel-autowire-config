<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use MelchiorKokernoot\LaravelAutowireConfig\LaravelAutowireConfigServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

use function class_basename;
use function config;

class TestCase extends Orchestra
{
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelAutowireConfigServiceProvider::class,
        ];
    }
}
