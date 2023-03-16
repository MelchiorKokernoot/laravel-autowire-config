<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config\Types;

use Attribute;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;

#[Attribute]
class NullableIntegerConfig extends ConfigValueWrapper
{
    public function value(): ?int
    {
        return Config::integerOrNull($this->key);
    }
}
