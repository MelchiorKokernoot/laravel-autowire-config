<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Strategies;

use Closure;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class AutowiringStrategy
{
    /**
     * @param ReflectionClass<AutowiresConfigs> $reflection
     * @throws ReflectionException
     */
    abstract public function wire(AutowiresConfigs $instance, ReflectionClass $reflection): void;

    /**
     * @param mixed $value Mixed because it could be any valid config value
     */
    protected function setPropertyValue(
        AutoWiresConfigs $object,
        ReflectionProperty $propertyToSet,
        mixed $value,
    ): void {
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
