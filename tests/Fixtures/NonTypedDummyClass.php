<?php

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableIntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableStringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;

class NonTypedDummyClass implements AutowiresConfigs
{
    public function __construct(
        public $fooBar = 'default',
    )
    {
    }
}
