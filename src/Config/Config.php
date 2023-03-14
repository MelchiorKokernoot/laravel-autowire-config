<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config;

use Attribute;
use TypeError;

use function config;
use function gettype;
use function is_array;
use function is_bool;
use function is_int;
use function is_null;
use function is_string;

#[Attribute]
final class Config
{
    /**
     * @return array<mixed> The shape of the array is not known, so we use mixed
     */
    public static function array(string $key): array
    {
        $configValue = self::get($key);

        if (!is_array($configValue)) {
            throw new TypeError('Expected config value of type array and got: ' . gettype($configValue));
        }

        return $configValue;
    }

    /**
     * @return array<mixed> The shape of the array is not known, so we use mixed
     */
    public static function arrayOrNull(string $key): ?array
    {
        $configValue = self::get($key);

        if (!is_null($configValue) && !is_array($configValue)) {
            throw new TypeError('Expected config value of type arrayOrNull and got: ' . gettype($configValue));
        }

        return $configValue;
    }

    public static function boolean(string $key): bool
    {
        $configValue = self::get($key);

        if (!is_bool($configValue)) {
            throw new TypeError('Expected config value of type boolean and got: ' . gettype($configValue));
        }

        return $configValue;
    }

    public static function integer(string $key): int
    {
        $configValue = self::get($key);

        if (!is_int($configValue)) {
            throw new TypeError('Expected config value of type integer and got: ' . gettype($configValue));
        }

        return $configValue;
    }

    public static function integerOrNull(string $key): ?int
    {
        $configValue = self::get($key);

        if (!is_int($configValue) && !is_null($configValue)) {
            throw new TypeError('Expected config value of type integerOrNull and got: ' . gettype($configValue));
        }

        return $configValue;
    }

    public static function string(string $key, string $default = ''): string
    {
        $configValue = self::get($key, $default);

        if (!is_string($configValue)) {
            throw new TypeError('Expected config value of type string and got: ' . gettype($configValue));
        }

        return $configValue;
    }

    public static function stringOrNull(string $key, ?string $default = null): ?string
    {
        $configValue = self::get($key, $default);

        if (!is_string($configValue) && !is_null($configValue)) {
            throw new TypeError('Expected config value of type stringOrNull and got: ' . gettype($configValue));
        }

        return $configValue;
    }

    private static function get(string $value, mixed $default = null): mixed
    {
        return config($value, $default);
    }
}
