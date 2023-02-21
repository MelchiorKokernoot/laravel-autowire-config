<?php

declare(strict_types=1);

namespace MelchiorKokernoot\LaravelAutowireConfig\Strategies;

use MelchiorKokernoot\LaravelAutowireConfig\Config\ConfigValueWrapper;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use function count;
use function is_subclass_of;

class AttributeStrategy extends AutowiringStrategy
{
    /**
     * @throws ReflectionException
     */
    public function wire(object $instance, ReflectionClass $reflection): void
    {
        if ($reflection->getConstructor() === null) {
            return;
        }

        foreach ($reflection->getConstructor()->getParameters() as $parameter) {
            $value = $this->getParameterValue($parameter);

            if ($value === null) {
                continue;
            }

            $this->setPropertyValue($instance, $reflection->getProperty($parameter->getName()), $value);
        }
    }

    /**
     * @throws ReflectionException
     */
    private function getParameterValue(mixed $parameter): mixed
    {
        foreach ($parameter->getAttributes() as $attribute) {
            if (!is_subclass_of($attribute->getName(), ConfigValueWrapper::class)) {
                continue;
            }

            if (count($attribute->getArguments()) !== 1) {
                throw new RuntimeException('ConfigValueWrapper attribute must have exactly one argument');
            }

            $configKey = $attribute->getArguments()[0];
            $configValueWrapperName = $parameter->getType()?->getName();
            return new $configValueWrapperName($configKey);
        }

        return null;
    }
}
