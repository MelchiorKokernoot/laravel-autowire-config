<?php

namespace MelchiorKokernoot\LaravelAutowireConfig\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MelchiorKokernoot\LaravelAutowireConfig\LaravelAutowireConfig
 */
class LaravelAutowireConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \MelchiorKokernoot\LaravelAutowireConfig\LaravelAutowireConfig::class;
    }
}
