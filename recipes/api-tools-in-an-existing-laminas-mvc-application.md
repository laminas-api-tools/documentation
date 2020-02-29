Adding API Tools to an Existing Project
=======================================

Because API Tools' functionality is provided by a number of Laminas MVC modules, you can add
API Tools to an existing Laminas application by adding its modules to the application.

You may skip this step, but for the purposes of the examples in this tutorial, we'll be using a Laminas
application based on [StatusLib](https://github.com/laminas-api-tools/statuslib-example) (which was used in
the [REST Service tutorial](/intro/first-rest-service.md).  To get a working Laminas MVC application like
it, please follow the directions in the [StatusLib README](https://github.com/laminas-api-tools/statuslib-example#statuslib-in-a-new-zf2-project).

Preparing a Laminas MVC application
-----------------------------------

Now that you have an existing Laminas MVC application you wish to add API Tools to, it is time to add the
dependencies.

```console
$ composer require "laminas-api-tools/api-tools:~1.0"
$ composer require --dev "laminas-api-tools/api-tools-admin:~1.0"
$ composer require --dev "laminas/laminas-development-mode:~2.0"
```

Now, to ensure that the development-time tools are accessible and cannot be accidentially deployed
in the production website, we need to make some modifications to the `public/index.php` file.
Replace: 

```php
// Run the application!
Laminas\Mvc\Application::init(require 'config/application.config.php')->run();
```

with:

```php
if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

$appConfig = include APPLICATION_PATH . '/config/application.config.php';

if (file_exists(APPLICATION_PATH . '/config/development.config.php')) {
    $appConfig = Laminas\Stdlib\ArrayUtils::merge($appConfig, include APPLICATION_PATH . '/config/development.config.php');
}

// Run the application!
Laminas\Mvc\Application::init($appConfig)->run();
```

Now, enable the necessary production modules by editing your `config/application.config.php`

```php
    /* ... */
    'modules' => [
        'Application',
        'Laminas\ApiTools',
        'Laminas\ApiTools\Provider',
        'AssetManager',
        'Laminas\ApiTools\ApiProblem',
        'Laminas\ApiTools\MvcAuth',
        'Laminas\ApiTools\OAuth2',
        'Laminas\ApiTools\Hal',
        'Laminas\ApiTools\ContentNegotiation',
        'Laminas\ApiTools\ContentValidation',
        'Laminas\ApiTools\Rest',
        'Laminas\ApiTools\Rpc',
        'Laminas\ApiTools\Versioning',
        'Laminas\DevelopmentMode',
        // any other modules you have...
    ],
    /* ... */
```

You'll notice the `Laminas\DevelopmentMode` module is included in `config/application.config.php`, which
we would intend is available when this application is deployed to production.  This is fine since
this particular module is responsible for only adding commands to the application to provide the
ability to switch development mode off and on on your development machine.

Next, we want to create a file called `config/development.config.php.dist`, with the following
content:

```php
<?php
/**
 * @license http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright Copyright (c) 2014 Zend Technologies USA Inc. (http://www.zend.com)
 */

return [
    // Development time modules
    'modules' => [
        'Laminas\ApiTools\Admin',
        'Laminas\ApiTools\Configuration',
    ],
    // development time configuration globbing
    'module_listener_options' => [
        'config_glob_paths' => ['config/autoload/{,*.}{global,local}-development.php'],
    ],
];
```

The above file is a template file used by `Laminas\DevelopmentMode`; when you call 
`php public/index.php development enable` from the command line, the module copies this file to
`config/development.config.php`, and your `public/index.php` now sees the file and merges it with
what `config/application.config.php` returns -- giving you your "development mode" settings.

`config/development.config.php` should never be checked into your version control system. By
omitting it, you can ensure that the application is production ready whenever a fresh checkout is
created. As such, add the line `config/development.config.php` to your `.gitignore` file;
afterwards, it should read something like the following:

```
vendor/
public/vendor/
config/development.config.php
config/autoload/local.php
config/autoload/*.local.php
!public/vendor/README.md
data/cache/*
!data/cache/.gitkeep
```

At this point, all the various peices that you would expect to find in the API Tools skeleton
application have been ported into your existing Laminas MVC application.  Finally, issue the following
command, just like you would in API Tools:

```console
$ php public/index.php development enable
```

Once complete, this particular Laminas MVC project can be accessed like any
other API Tools project.

Building API Tools API modules
------------------------------

At this point there are effectively two ways of building out API Tools modules:

- New API modules that consume existing module's models.
- Creating services inside an existing module.

There are a couple of important notes to remember:

- API Tools does not modify code inside the `vendor` directory.  This means your modules need to
  exist in the `module` directory.
- API Tools will create a specific directory structure inside the module's source code:
  - When services are created, they will be created as [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
    compatible classes in the specified module source directory.
  - The naming and namespace pattern for these classes will be 
    `{Namespace}\V{Version Number}\Rest|Rpc\{Service Name}`

Choosing to go the route of having separate API modules will ensure a higher level of separation of
concerns between modules. The unfortunate downside to this is that there will be more modules, and
thus a higher chance of naming collisions.

### API Tools-enabling existing modules

In order to enable an existing module as an API Tools module, ensure the module is in the `module`
directory; then perform one of the following.

#### Manually enabling a module

Edit the module class by hand to implement the `ApiToolsProviderInterface` (which is a [marker
interface](http://en.wikipedia.org/wiki/Marker_interface_pattern)).

Using `StatusLib` as an example, we would edit `module/StatusLib/Module.php`:

```php
/* ... */
use Laminas\ApiTools\Provider\ApiToolsProviderInterface;

class Module implements ApiToolsProviderInterface
{
    /* ... */
```

#### Using the API Tools Admin API

You can also use the API Tools Admin API to API Tools-enable the module.

To do this, you will need a web server running your application; this can be the built-in PHP web
server, as detailed in the [installation guide](/intro/installation.md#all-methods):

```console
php -S 0.0.0.0:8888 -ddisplay_errors=0 -t public public/index.php
```

Once running, initiate a `PUT` request to the `/api-tools/api/module.enable` path, providing the
module name as the `module` variable of the payload:

```HTTP
PUT /api-tools/api/module.enable HTTP/1.1
Accept: application/json
Content-Type: application/json

{"module":"StatusLib"}
```

### Consuming existing services

In new API services you create, you can consume any other services you've already created in your
application. As an example, we could consume the `StatusLib` mapper inside a newly minted REST
service resource with the name `Status`.  

To do this, we'd edit the factory for the `StatusResource` to pass the mapper as a constructor
argument:

```php    
// In module/StatusLib/src/StatusLib/V1/Rest/Status/StatusResourceFactory.php :
namespace Status\V1\Rest\Status;

class StatusResourceFactory
{
    public function __invoke($services)
    {
        return new StatusResource($services->get('StatusLib\Mapper'));
    }
}
```

Next, we'd edit our `StatusResource` to accept the argument and assign it to a property:

```php
// In module/StatusLib/src/StatusLib/V1/Rest/Status/StatusResource.php :
/* ... */
use StatusLib\MapperInterface;

class StatusResource extends AbstractResourceListener
{
    protected $mapper;

    public function __construct(MapperInterface $statusMapper)
    {
        $this->mapper = $statusMapper;
    }
    
    /* ... */
}
```

Now we can consume the mapper within a method; below shows how we'd do so from `fetchAll()`.

```php
/* ... */
class StatusResource extends AbstractResourceListener
{
    /* ... */

    public function fetchAll($params = [])
    {
        return $this->statusMapper->fetchAll();
    }

    /* ... */
}
```

This technique can be performed for any API service, and using any service exposed in your
application.
