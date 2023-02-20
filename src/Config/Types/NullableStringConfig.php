<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config\Types;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;

class NullableStringConfig extends ConfigValueWrapper
{
    public function value(): ?string
    {
        return Config::stringOrNull($this->key);
    }
}
