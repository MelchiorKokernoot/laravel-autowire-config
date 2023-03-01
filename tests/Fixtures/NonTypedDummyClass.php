<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Tests\Fixtures;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Types\StringConfig;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;

class NonTypedDummyClass implements AutowiresConfigs
{
    public function __construct(
        #[NonTypedConfig('foo.bar')]
        public $fooBar = 'default',
        #[StringConfig('foo.string')]
        public $fooString = 'default',
    ) {
    }
}
