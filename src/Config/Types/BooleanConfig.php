<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config\Types;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;

class BooleanConfig extends ConfigValueWrapper
{
    public function value(): bool
    {
        return Config::boolean($this->key);
    }
}
