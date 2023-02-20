<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config;

abstract class ConfigValueWrapper
{
    protected readonly string $key;

    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    abstract public function value(): mixed;

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
