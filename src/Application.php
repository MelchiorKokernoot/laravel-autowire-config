<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig;

use Illuminate\Contracts\Container\BindingResolutionException;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;
use ReflectionParameter;
use RuntimeException;

use function config;
use function count;

class Application extends \Illuminate\Foundation\Application
{
    /**
     * @return mixed Always a primitive
     * @throws BindingResolutionException
     */
    protected function resolvePrimitive(ReflectionParameter $parameter): mixed
    {
        try {
            return parent::resolvePrimitive($parameter);
        } catch (BindingResolutionException $e) {
            //At this point, usually the error would be unrecoverable,
            //but we can get the parameter value from the config helper
            $wiredConfigValue = $this->getPropertyValue($parameter);

            if (!$wiredConfigValue) {
                throw $e;
            }

            return $wiredConfigValue;
        }
    }

    private function getPropertyValue(ReflectionParameter $parameter): mixed
    {
        foreach ($parameter->getAttributes() as $attribute) {
            $attributeName = $attribute->getName();

            if ($attributeName !== Config::class && $attributeName !== ConfigValueWrapper::class) {
                continue;
            }

            if (count($attribute->getArguments()) !== 1) {
                throw new RuntimeException('ConfigValueWrapper attribute must have exactly one argument');
            }

            $configKey = $attribute->getArguments()[0];

            return config($configKey);
        }

        return null;
    }
}
