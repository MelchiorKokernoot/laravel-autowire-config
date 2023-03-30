<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use MelchiorKokernoot\LaravelAutowireConfig\LaravelAutowireConfigServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

use function config;

class TestCase extends Orchestra
{
    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter, SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint -- baseline
    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');
    }

    // phpcs:ignore SlevomatCodingStandard.Functions.UnusedParameter.UnusedParameter, SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingAnyTypeHint, SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification -- baseline
    protected function getPackageProviders($app): array
    {
        return [
            LaravelAutowireConfigServiceProvider::class,
        ];
    }
}
