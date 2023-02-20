<?php

namespace MelchiorKokernoot\LaravelAutowireConfig\Config\Types;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;

class MixedConfig extends ConfigValueWrapper
{
    public function value(): mixed
    {
        return Config::mixed($this->key);
    }
}
