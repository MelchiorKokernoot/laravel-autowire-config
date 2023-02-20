<?php

namespace MelchiorKokernoot\LaravelAutowireConfig;

use App\Helpers\Config\ConfigValueWrapper;
use Closure;
use Illuminate\Support\Str;
use MelchiorKokernoot\LaravelAutowireConfig\Contracts\AutowiresConfigs;
use ReflectionClass;
use ReflectionParameter;
use ReflectionProperty;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use MelchiorKokernoot\LaravelAutowireConfig\Commands\LaravelAutowireConfigCommand;

class LaravelAutowireConfigServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-autowire-config')
            ->hasConfigFile()
            ->hasViews();
    }

    public function boot(): void
    {
        parent::boot();
        $this->bootConfigAutowiring();
    }

    private function bootConfigAutowiring(): void
    {
        $this->app->afterResolving(function ($object): void {
            if (!$object instanceof AutoWiresConfigs) {
                return;
            }

            $reflection = new ReflectionClass($object);

            foreach ($reflection->getConstructor()?->getParameters() as $parameter) {
                $type = $parameter->getType();

                if ($type === null) {
                    continue;
                }

                if (!is_subclass_of($type->getName(), ConfigValueWrapper::class)) {
                    continue;
                }

                $propertyToSet = $reflection->getProperty($parameter->getName());
                $guessedConfigKey = $this->guessConfigKey($parameter);
                $configValueWrapperName = $type->getName();
                $value = (new $configValueWrapperName())->setKey($guessedConfigKey);
                $this->setPropertyValue($object, $propertyToSet, $value);
            }
        });
    }

    public function guessConfigKey(ReflectionParameter $parameter): string
    {
        return Str::of($parameter->getName())->kebab()->replace('-', '.')->value();
    }

    private function bindClosure($obj, $attribute): bool|Closure|null
    {
        $setter = function ($value) use ($attribute): void {
            $this->{$attribute} = $value;
        };

        return Closure::bind($setter, $obj, $obj::class);
    }

    private function setPropertyValue(AutoWiresConfigs $object, ReflectionProperty $propertyToSet, $value): void
    {
        $valueSetterClosure = $this->bindClosure($object, $propertyToSet->getName());
        $valueSetterClosure($value);
    }
}
