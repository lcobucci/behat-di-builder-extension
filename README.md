# lcobucci/di-builder extension for behat

[![Total Downloads]](https://packagist.org/packages/lcobucci/behat-di-builder-extension)
[![Latest Stable Version]](https://packagist.org/packages/lcobucci/behat-di-builder-extension)
[![Unstable Version]](https://packagist.org/packages/lcobucci/behat-di-builder-extension)

[![Build Status]](https://github.com/lcobucci/behat-di-builder-extension/actions?query=workflow%3A%22PHPUnit%20Tests%22+branch%3A1.1.x)
[![Code Coverage]](https://codecov.io/gh/lcobucci/behat-di-builder-extension)

Allows injecting services from a container created using [`lcobucci/di-builder`](http://packagist.org/packages/lcobucci/di-builder)
in a Behat context.

## Installation

Package is available on [Packagist], you can install it using [Composer].

```shell
composer require --dev lcobucci/behat-di-builder-extension
```

## Basic usage

You must first enable the extension on your behat configuration file:

```yaml
default:
  # ...
  
  extensions:
      Lcobucci\DependencyInjection\Behat\BuilderExtension: ~
```

Then enable the use of the test container (create by the extension) in the suite configuration:

```yaml
default:
  suites:
    my-suite:
      services: "@test_container"
  
  extensions:
      Lcobucci\DependencyInjection\Behat\BuilderExtension: ~
```

And finally inject the services into your contexts

```yaml
default:
  suites:
    my-suite:
      services: "@test_container"
      contexts:
        - My\Lovely\Context:
          - "@my_service"
  
  extensions:
    Lcobucci\DependencyInjection\Behat\BuilderExtension: ~
```

## Advanced configuration

You can provide the following parameters to the extension to better configure it:

* **name**: if you already have an extension using `test_container`
* **container_builder**: if your application already uses `lcobucci/di-builder` and you
want to use it (instead of a blank container builder)
* **packages**: so that you can add/override service definitions for testing

Your `behat.yml` would look like this with those settings: 

```yaml
default:
  suites:
    my-suite:
      services: "@my_container"
      contexts:
        - My\Lovely\Context:
          - "@my_service"
  
  extensions:
    Lcobucci\DependencyInjection\Behat\BuilderExtension:
      name: "my_container"
      container_builder: "config/container_builder.php"
      packages:
        My\DIBuilder\Package: ~
        My\DIBuilder\PackageWithConstructorArguments:
          - "one"
          - "two"
```

[Total Downloads]: https://img.shields.io/packagist/dt/lcobucci/behat-di-builder-extension.svg?style=flat-square
[Latest Stable Version]: https://img.shields.io/packagist/v/lcobucci/behat-di-builder-extension.svg?style=flat-square
[Unstable Version]: https://img.shields.io/packagist/vpre/lcobucci/behat-di-builder-extension.svg?style=flat-square
[Build Status]: https://img.shields.io/github/workflow/status/lcobucci/behat-di-builder-extension/PHPUnit%20tests/1.1.x?style=flat-square
[Code Coverage]: https://codecov.io/gh/lcobucci/behat-di-builder-extension/branch/1.1.x/graph/badge.svg
[Packagist]: http://packagist.org/packages/lcobucci/behat-di-builder-extension
[Composer]: http://getcomposer.org
