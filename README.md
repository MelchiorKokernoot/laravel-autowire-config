![Banner](https://banners.beyondco.de/laravel-autowire-configs.png?theme=light&packageManager=composer+require&packageName=melchiorkokernoot%2Flaravel-autowire-config&pattern=circuitBoard&style=style_1&description=Allows+configuration+injection+through+auto-wired+constructor+arguments&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

# Configuration autowiring and injection

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melchiorkokernoot/laravel-autowire-config.svg?style=flat-square)](https://packagist.org/packages/melchiorkokernoot/laravel-autowire-config)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/melchiorkokernoot/laravel-autowire-config/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/melchiorkokernoot/laravel-autowire-config/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/melchiorkokernoot/laravel-autowire-config.svg?style=flat-square)](https://packagist.org/packages/melchiorkokernoot/laravel-autowire-config)

Enable laravel configuration injection through auto-wired constructor arguments.

```php
class Foo{
    public function __construct(
        public StringConfig $appName,
    ){}
}
```

## Installation

You can install the package via composer:

```bash
composer require melchiorkokernoot/laravel-autowire-config
```

## Usage

Firstly, implement the `AutowireConfig` interface on your service provider.
Typehint one of the typed config classes in your constructor, and use the camelCase version of the config key as the
property name.

```php
class Foo implements AutowireConfig{
    public function __construct(
        public StringConfig $appName,
    ){}
}
```

When using this class from the container (through dependency injection e.g.), the config value will be injected as if
you do this:

```php
$foo = new Foo(config('app.name'));
```

The benefit of this, is that you keep a clear separation between your application logic and your configuration layer.
No more service locators, no more `config()` calls in your code, just clean dependencies.

## Typed config classes

The following config classes are available:

- `ArrayConfig`
- `BooleanConfig`
- `IntegerConfig`
- `NullableArrayConfig`
- `NullableIntegerConfig`
- `NullableStringConfig`
- `StringConfig`

## Testing

```bash
composer test
```

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
