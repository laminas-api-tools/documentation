Laminas RPC
======

Introduction
------------

Module for implementing RPC web services in Laminas.

Enables:

- defining controllers as PHP callables.
- creating a whitelist of HTTP request methods; requests outside the whitelist will return a `405
  Method Not Allowed` response with an `Allow` header indicating allowed methods.

Requirements
------------
  
Please see the [composer.json](https://github.com/laminas-api-tools/api-tools-rpc/tree/master/composer.json) file.

Installation
------------

Run the following `composer` command:

```console
$ composer require laminas-api-tools/api-tools-rpc
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-rpc": "^1.3"
}
```

And then run `composer update` to ensure the module is installed.

Finally, add the module name to your project's `config/application.config.php` under the `modules`
key:

```php
return [
    /* ... */
    'modules' => [
        /* ... */
        'Laminas\ApiTools\Rpc',
    ],
    /* ... */
];
```

> ### laminas-component-installer
>
> If you use [laminas-component-installer](https://github.com/laminas/laminas-component-installer),
> that plugin will install api-tools-rpc as a module for you.

Configuration
=============

### User Configuration

This module uses the top-level configuration key of `api-tools-rpc`.

#### Key: Controller Service Name

The `api-tools-rpc` module uses a mapping between controller service names with the values being an array
of information that determine how the RPC style controller will behave.  The key should be a
controller service name that also matches a controller service name assigned to a route in the
`router` configuration.

Inside this key, the following sub-keys are required:

- `http_methods`: for configuring what methods this RPC service controller can respond to. This also
  is used for populating the `Allow` response header for this service.
- `route_name`: for linking back to a particular route.  This is especially useful when RPC routes
  need to build links as part of their response.
- `callable` (optional): utilized to specify a callable that will be invoked at dispatch time.  At
  dispatch time, these callables are typically wrapped in an instance of `Laminas\ApiTools\Rpc\RpcController`,
  which is a dispatchable action controller.

Example:

```php
'api-tools-rpc' => [
    'Application\Controller\LoginController' => [
        'http_methods' => ['POST'],
        'route_name'   => 'api-login',
        'callable'     => 'Application\Controller\LoginController::process',
    ],
],
```

### System Configuration

The following configuration ensures this module operates properly in the context of a Laminas
application:

```php
'controllers' => [
    'abstract_factories' => [
        'Laminas\ApiTools\Rpc\Factory\RpcControllerFactory',
    ],
],
```

Laminas Events
==========

### Listeners

#### Laminas\ApiTools\Rpc\OptionsListener

This listeners is registered to the `MvcEvent::EVENT_ROUTE` event with a priority of `-100`.  It is
responsible for ensuring the HTTP response to an `OPTIONS` request for the given RPC service
includes the properly configured and allowed HTTP methods in the `Allow` header.  This uses the
configuration from the `http_methods` key of the `api-tools-rpc` service configuration for the matching
service. Additionally, it verifies if the incoming request method is in the configured
`http_methods` for the RPC service, and, if not, returns a `405 Method Not Allowed` response with a
populated `Allow` header.

Laminas Services
============

### Models

#### Laminas\ApiTools\Rpc\ParameterMatcher

This particular model is used and is useful for taking a callable and a set of named parameters,
and determining which ones can be used as arguments to the callable.

### Controller

#### Laminas\ApiTools\Rpc\RpcController

This controller is used to wrap a callable registered as an RPC service in order to make it a Laminas
dispatchable.
