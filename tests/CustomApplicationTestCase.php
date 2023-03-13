<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests;

use MelchiorKokernoot\LaravelAutowireConfig\Application;
use Orchestra\Testbench\Foundation\PackageManifest;

use function tap;

class CustomApplicationTestCase extends TestCase
{
    protected function resolveApplication()
    {
        return tap(new Application($this->getBasePath()), function ($app): void {
            $app->bind(
                'Illuminate\Foundation\Bootstrap\LoadConfiguration',
                'Orchestra\Testbench\Bootstrap\LoadConfiguration',
            );

            PackageManifest::swap($app, $this);
        });
    }
}
