<?php

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\ArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\BooleanConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\IntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableIntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableStringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\StringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use MelchiorKokernoot\LaravelAutowireConfig\Tests\DummyDependency;

class NullableDummyClass implements AutowiresConfigs
{
    public function __construct(
        public NullableStringConfig  $fooString,
        public NullableArrayConfig   $fooArray,
        public NullableIntegerConfig $fooInt,
    )
    {
    }
}
