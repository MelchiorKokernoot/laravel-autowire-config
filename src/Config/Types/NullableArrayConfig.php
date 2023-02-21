<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config\Types;

use Attribute;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;

#[Attribute]
class NullableArrayConfig extends ConfigValueWrapper
{
    /**
     * @return array<mixed> The shape of the array is not known, so we use mixed
     */
    public function value(): ?array
    {
        return Config::arrayOrNull($this->key);
    }
}
