![Banner](https://banners.beyondco.de/laravel-autowire-configs.png?theme=light&packageManager=composer+require&packageName=melchiorkokernoot%2Flaravel-autowire-config&pattern=circuitBoard&style=style_1&description=Allows+configuration+injection+through+auto-wired+constructor+arguments&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# Configuration autowiring and injection

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melchiorkokernoot/laravel-autowire-config.svg?style=flat-square)](https://packagist.org/packages/melchiorkokernoot/laravel-autowire-config)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/melchiorkokernoot/laravel-autowire-config/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/melchiorkokernoot/laravel-autowire-config/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/melchiorkokernoot/laravel-autowire-config.svg?style=flat-square)](https://packagist.org/packages/melchiorkokernoot/laravel-autowire-config)

Enable laravel configuration injection through auto-wired constructor arguments.

```php
class Foo {
    public function __construct(
        #[Config('app.name')]
        public string $myConfiguredAppName,
    ){}
}
```

  * [Installation](#installation)
  * [Configuration](#configuration)
  * [Usage](#usage)
    + [Usage through Custom Application class (Recommended)](#usage-through-custom-application-class-recommended)
    + [Usage through Service provider](#usage-through-service-provider)
      - [Usage through attribute autowiring (AttributeStrategy)](#usage-through-attribute-autowiring-attributestrategy)
      - [Usage through constructor property name autowiring (PropNameStrategy)](usage-through-constructor-property-name-autowiring-propnamestrategy)
      - [Accessing the config values](#accessing-the-config-values)
      - [Typed config classes](#typed-config-classes)
      - [Pitfalls](#pitfalls)
  * [Testing](#testing)
  * [Roadmap](#roadmap)
  * [Changelog](#changelog)
  * [Contributing](#contributing)
  * [Security Vulnerabilities](#security-vulnerabilities)
  * [Credits](#credits)
  * [License](#license)


## Installation

You can install the package via composer:

```bash
composer require melchiorkokernoot/laravel-autowire-config
```

## Configuration

The package will automatically register itself.
You can publish the config file with:

```bash
php artisan vendor:publish --provider="MelchiorKokernoot\LaravelAutowireConfig\LaravelAutowireConfigServiceProvider"
```


> Note that it is not necessary to publish the config file, nor is it necessary to configure the package.
Only change the config file when you know why you are doing it.


This is the contents of the published config file:

```php
return [
    //Either AttributeStrategy::class  or AutowiredPropNameStrategy::class
    'strategy' => PropNameStrategy::class,
];
```

You can choose between two strategies:

- `AttributeStrategy` : Uses attributes to inject the config value.
- `PropNameStrategy` (default): Uses constructor promoted property names to inject the config values.

More on the strategies below.

## Usage 
### Usage through Custom Application class (Recommended)

Starting from version 3.0.0, the package can be used through a custom `Application` class. This is the recommended way
of using the package, as it will offer a more "natural" way of using the package.

The only thing one needs to do to enable this behaviour, is to swap out the default `Application` class in
bootstrap/app.php
with the `MelchiorKokernoot\LaravelAutowireConfig\Application` class.

So change this:

```php
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__),
);
```

Into this:

```php
use MelchiorKokernoot\LaravelAutowireConfig\Application;

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__),
);
```

Advantages of using the custom application class:

- Allows for primitive datatypes (int, float, bool, string) to be injected directly into the constructor, without a
  default value. No more need for wrapping the config value in a typed config class!
- Allows using readonly properties in the constructor

Currently this only works with the attribute strategy, but you do not need to configure this. Once you start using the
new `Application` class, the package will automatically disable old way of using the package, so no resources are wasted
on duplicate resolution.

To demonstrate this, let's take a look at the following example:

```php
class Foo {
    public function __construct(
        #[Config('app.name','default value')]
        public string $myConfiguredAppName,
    ){}
}
```

When resolving this `Foo` class from the container, the package will automatically resolve the config value for
`app.name` and inject it into the `$myConfiguredAppName` property.

### Usage through Service provider

Starting from Version 2.0.0 the package can be used in two ways:

#### Usage through attribute autowiring (AttributeStrategy)

Firstly, implement the `AutowiresConfigs` interface on your class.
Typehint one of the [typed config classes](#typed-config-classes) in your constructor, and use that typehint as an
attribute on the property, finally pass the config key as the attribute value.

```php
class Foo implements AutowiresConfigs{
    public function __construct(
        #[StringConfig('app.name')]
        public StringConfig $appName,
    ){}
}
```

> Note you do not have to name the variable to match the config key now, but you still have to type-hint the config
> class.

When using this class from the container (through dependency injection e.g.), the config value will be injected as if
you do this:

```php
$foo = new Foo(config('app.name'));
```

#### Usage through constructor property name autowiring (PropNameStrategy)

Firstly, implement the `AutowiresConfigs` interface on your class.
Typehint one of the [typed config classes](#typed-config-classes) in your constructor, and use the camelCase version of
the config key as the
property name.

```php
class Foo implements AutowiresConfigs{
    public function __construct(
        public StringConfig $appName,
    ){}
}
```

> You need to match the config key to the property name, so `app.name` will become `appName`.

When using this class from the container (through dependency injection e.g.), the config value will be injected as if
you do this:

```php
$foo = new Foo(config('app.name'));
```

The benefit of this, is that you keep a clear separation between your application logic and your configuration layer.
No more service locators, no more `config()` calls in your code, just clean dependencies.

#### Accessing the config values

```
This way of access is only required when NOT using the custom Application strategy
```

Because the config values are wrapped in a typed config class, you cannot access the value directly. Instead, you can
access the value through the `value` method. For convenience, the `__toString` magic method is also implemented, so you
can use the config value as a string (in the case of a value that can be casted to a string,of course) directly.
Furthermore, the shorthands `$object->config->v()`and `$object->config->v()` are also available for accessing the value.

```php
class Foo implements AutowiresConfigs{
    public function __construct(
        public StringConfig $appName,
    ){}

    public function bar(){
        //Casting to string
        (string) $this->appName
        
        //Shorthand method call
        $this->appName->value->v();
        
        //Shorthand property access
        $this->appName->value->v;
        
        //Ordinary method call
        return $this->appName->value();
    }
}
```

#### Typed config classes

The following config classes are available:

- `ArrayConfig`
- `BooleanConfig`
- `IntegerConfig`
- `NullableArrayConfig`
- `NullableIntegerConfig`
- `NullableStringConfig`
- `StringConfig`

#### Pitfalls

This package hooks into the afterResolving callback, which means that it will only work for classes that are resolved
through the container. This that the config values will only be populated after the constructor has been called, so the
values will not be available in the constructor.

## Testing

```bash
composer test
```

## Roadmap

- [X] Add support for primitive types when using the custom `Application` class
- [X] Add support for readonly properties when using the custom `Application` class
- [ ] Add support for configuring the way values should be unwrapped (disable shorthand `v()` for example)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [MelchiorKokernoot](https://github.com/MelchiorKokernoot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
