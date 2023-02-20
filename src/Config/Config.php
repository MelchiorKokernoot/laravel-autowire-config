<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Config;

use Webmozart\Assert\Assert;

use function config;

final class Config
{
    /**
     * @return array<mixed> The shape of the array is not known, so we use mixed
     */
    public static function array(string $key): array
    {
        $configValue = self::get($key);

        Assert::isArray($configValue);

        return $configValue;
    }

    /**
     * @return array<mixed> The shape of the array is not known, so we use mixed
     */
    public static function arrayOrNull(string $key): ?array
    {
        $configValue = self::get($key);

        Assert::nullOrIsArray($configValue);

        return $configValue;
    }

    public static function boolean(string $key): bool
    {
        $configValue = self::get($key);

        Assert::boolean($configValue);

        return $configValue;
    }

    public static function integer(string $key): int
    {
        $configValue = self::get($key);

        Assert::numeric($configValue);

        return (int) $configValue;
    }

    public static function integerOrNull(string $key): ?int
    {
        $configValue = self::get($key);

        Assert::nullOrInteger($configValue);

        return $configValue;
    }

    public static function string(string $key, string $default = ''): string
    {
        $configValue = self::get($key, $default);

        Assert::string($configValue);

        return $configValue;
    }

    public static function stringOrNull(string $key, ?string $default = null): ?string
    {
        $configValue = self::get($key, $default);

        Assert::nullOrString($configValue);

        return $configValue;
    }

    private static function get(string $value, mixed $default = null): mixed
    {
        return config($value, $default);
    }
}
