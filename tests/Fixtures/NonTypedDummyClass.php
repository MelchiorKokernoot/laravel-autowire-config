<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures;

use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;

class NonTypedDummyClass implements AutowiresConfigs
{
    public function __construct(
        public $fooBar = 'default',
    )
    {
    }
}
