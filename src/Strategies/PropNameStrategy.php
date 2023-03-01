<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Strategies;

use Illuminate\Support\Str;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

use function is_subclass_of;

class PropNameStrategy extends AutowiringStrategy
{
    public function wire(AutowiresConfigs $instance, ReflectionClass $reflection): void
    {
        foreach ($reflection->getConstructor()?->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (!($type instanceof ReflectionNamedType)) {
                continue;
            }

            $typeClassName = $type->getName();

            if (!is_subclass_of($typeClassName, ConfigValueWrapper::class)) {
                continue;
            }

            $propertyToSet = $reflection->getProperty($parameter->getName());
            $guessedConfigKey = $this->guessConfigKey($parameter);
            $configValueWrapperName = $typeClassName;
            $value = new $configValueWrapperName($guessedConfigKey);
            $this->setPropertyValue($instance, $propertyToSet, $value);
        }
    }

    private function guessConfigKey(ReflectionParameter $parameter): string
    {
        return Str::of($parameter->getName())->kebab()->replace('-', '.')->value();
    }
}
