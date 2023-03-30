<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig;

use Illuminate\Contracts\Container\BindingResolutionException;
use MelchiorKokernoot\LaravelAutowireConfig\Config\Config;
use ReflectionParameter;

use function config;

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

            if ($wiredConfigValue === null) {
                throw $e;
            }

            return $wiredConfigValue;
        }
    }

    private function getPropertyValue(ReflectionParameter $parameter): mixed
    {
        foreach ($parameter->getAttributes(Config::class) as $attribute) {
            $instance = $attribute->newInstance();
            return config($instance->key, $instance->default);
        }

        return null;
    }
}
