<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableIntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\NullableStringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;

class NullableDummyClass implements AutowiresConfigs
{
    public function __construct(
        public NullableStringConfig $fooString,
        public NullableArrayConfig $fooArray,
        public NullableIntegerConfig $fooInt,
    )
    {
    }
}
