# lcobucci/di-builder extension for behat

[![Total Downloads](https://img.shields.io/packagist/dt/lcobucci/behat-di-builder-extension.svg?style=flat-square)](https://packagist.org/packages/lcobucci/behat-di-builder-extension)
[![Latest Stable Version](https://img.shields.io/packagist/v/lcobucci/behat-di-builder-extension.svg?style=flat-square)](https://packagist.org/packages/lcobucci/behat-di-builder-extension)
[![Unstable Version](https://img.shields.io/packagist/vpre/lcobucci/behat-di-builder-extension.svg?style=flat-square)](https://packagist.org/packages/lcobucci/behat-di-builder-extension)

![Branch master](https://img.shields.io/badge/branch-master-brightgreen.svg?style=flat-square)
[![Build Status](https://img.shields.io/travis/lcobucci/behat-di-builder-extension/master.svg?style=flat-square)](http://travis-ci.org/#!/lcobucci/behat-di-builder-extension)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/lcobucci/behat-di-builder-extension/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/lcobucci/behat-di-builder-extension/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/lcobucci/behat-di-builder-extension/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/lcobucci/behat-di-builder-extension/?branch=master)

Allows injecting services from a container created using [`lcobucci/di-builder`](http://packagist.org/packages/lcobucci/di-builder)
in a Behat context.

## Installation

Package is available on [Packagist](http://packagist.org/packages/lcobucci/behat-di-builder-extension),
you can install it using [Composer](http://getcomposer.org).

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
