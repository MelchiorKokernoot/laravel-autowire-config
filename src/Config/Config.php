<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config;

use Attribute;

use function assert;
use function config;
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

        assert(is_array($configValue));

        return $configValue;
    }

    /**
     * @return array<mixed> The shape of the array is not known, so we use mixed
     */
    public static function arrayOrNull(string $key): ?array
    {
        $configValue = self::get($key);

        assert(is_null($configValue) || is_array($configValue));

        return $configValue;
    }

    public static function boolean(string $key): bool
    {
        $configValue = self::get($key);

        assert(is_bool($configValue));

        return $configValue;
    }

    public static function integer(string $key): int
    {
        $configValue = self::get($key);

        assert(is_int($configValue));

        return $configValue;
    }

    public static function integerOrNull(string $key): ?int
    {
        $configValue = self::get($key);

        assert(is_int($configValue) || is_null($configValue));

        return $configValue;
    }

    public static function string(string $key, string $default = ''): string
    {
        $configValue = self::get($key, $default);

        assert(is_string($configValue));

        return $configValue;
    }

    public static function stringOrNull(string $key, ?string $default = null): ?string
    {
        $configValue = self::get($key, $default);

        assert(is_string($configValue) || is_null($configValue));

        return $configValue;
    }

    private static function get(string $value, mixed $default = null): mixed
    {
        return config($value, $default);
    }
}
