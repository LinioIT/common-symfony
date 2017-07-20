Linio Common Symfony
============
Linio's Symfony Common library contains small components that either extend Symfony's functionality or provide
a coherent base for Linio's symfony projects:

* Service traits
* Controller helper methods

Install
-------

The recommended way to install Linio Common Symfony is [through composer](http://getcomposer.org).

```
$ composer require linio/common-symfony
```

Tests
-----

To run the test suite, you need install the dependencies via composer, then
run PHPUnit.

    $ composer install
    $ vendor/bin/phpunit

Service Traits
--------------

The common library includes service traits, so you can easily inject dependencies
and make use of services in your controllers. There are various controller-specific
traits available:

* `AuthorizationAware`: allows you to use the `symfony/security` component and provides helpers
* `FlashMessageAware`: allows you to use the `symfony/http-foundation-session` component and provides flash message helpers
* `FormAware`: allows you to use the `symfony/form` component and provides helpers
* `RouterAware`: allows you to use the `symfony/routing` component and provides helpers
* `SessionAware`: allows you to use the `symfony/http-foundation/session` component and provides helpers
* `TemplatingAware`: allows you to use the `symfony/templating` component and provides helpers
