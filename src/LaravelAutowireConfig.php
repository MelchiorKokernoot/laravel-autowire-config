<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig;

use Closure;
use Illuminate\Support\Str;
use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use MelchiorKokernoot\LaravelAutowireConfig\Exceptions\ConfigAutoWiringException;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;

use function is_subclass_of;

class LaravelAutowireConfig
{
    public function getAfterResolvingClosure(): Closure
    {
        return function (object|string $object): void {
            if (!$object instanceof AutoWiresConfigs) {
                return;
            }

            $reflection = new ReflectionClass($object);
            $reflectionParameters = $reflection->getConstructor()?->getParameters();

            if ($reflectionParameters === null || $reflectionParameters === []) {
                return;
            }

            foreach ($reflectionParameters as $parameter) {
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
                $this->setPropertyValue($object, $propertyToSet, $value);
            }
        };
    }

    private function guessConfigKey(ReflectionParameter $parameter): string
    {
        return Str::of($parameter->getName())->kebab()->replace('-', '.')->value();
    }

    /**
     * @param mixed $value Mixed because it could be any valid config value
     * @throws ConfigAutoWiringException
     */
    private function setPropertyValue(
        AutoWiresConfigs $object,
        ReflectionProperty $propertyToSet,
        mixed $value,
    ): void
    {
        $valueSetterClosure = $this->bindClosure($object, $propertyToSet->getName());
        $valueSetterClosure($value);
    }

    private function bindClosure(object $obj, string $property): Closure
    {
        $setter = function ($value) use ($property): void {
            $this->{$property} = $value;
        };

        return Closure::bind($setter, $obj, $obj::class);
    }
}
