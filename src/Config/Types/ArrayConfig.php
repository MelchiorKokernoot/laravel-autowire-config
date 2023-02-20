<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config\Types;

use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;

class ArrayConfig extends ConfigValueWrapper
{
    /**
     * @return array<mixed> The shape of the array is not known, so we use mixed
     */
    public function value(): array
    {
        return Config::array($this->key);
    }
}
