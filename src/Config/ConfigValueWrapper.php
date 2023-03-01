<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config;

use RuntimeException;

use function is_bool;
use function is_null;
use function is_numeric;
use function is_string;

/**
 * @property-read mixed $v
 */
abstract class ConfigValueWrapper
{
    private const V = 'v';

    public function __construct(
        protected readonly string $key = '',
    )
    {
    }

    abstract public function value(): mixed;

    /**
     * Shorthand for value()
     */
    public function v(): mixed
    {
        return $this->value();
    }

    public function __toString(): string
    {
        $value = $this->value();
        $stringable = is_numeric($value) || is_string($value) || is_bool($value);

        return match (true) {
            $stringable => (string) $value,
            is_null($value) => '',
            default => throw new RuntimeException('Cannot convert value to string'),
        };
    }

    /**
     * @unused array $arguments
     */
    public function __get(string $name, array $arguments): mixed
    {
        if ($name !== self::V) {
            return null;
        }

        return $this->value();
    }
}
