<?php

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\ArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\BooleanConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\IntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\StringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;

class DummyClass implements AutowiresConfigs
{
    public function __construct(
        private StringConfig   $appName,
        public StringConfig    $fooBar,
        public IntegerConfig   $testInteger,
        public ArrayConfig     $fooArray,
        public BooleanConfig   $testBoolean,
        public DummyDependency $dummyDependency,
    )
    {
    }

    public function getAppName(): string
    {
        return $this->appName->value();
    }
}
