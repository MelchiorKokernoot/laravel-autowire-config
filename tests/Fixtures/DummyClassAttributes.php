<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\ArrayConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\BooleanConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\IntegerConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\StringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;

class DummyClassAttributes implements AutowiresConfigs
{
    public function __construct(
        #[StringConfig('app.name')]
        private StringConfig $appName,
        #[StringConfig('foo.bar')]
        public StringConfig $fooBar,
        #[IntegerConfig('test.integer')]
        public IntegerConfig $testInteger,
        #[ArrayConfig('foo.array')]
        public ArrayConfig $fooArray,
        #[BooleanConfig('test.boolean')]
        public BooleanConfig $testBoolean,
        public DummyDependency $dummyDependency,
    )
    {
    }

    public function getAppName(): string
    {
        return $this->appName->value();
    }
}
