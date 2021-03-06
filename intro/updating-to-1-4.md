Updating to version 1.4
=======================

Version 1.4 updates Laminas API Tools to work with version 3 releases of Laminas MVC
components. While every attempt was made to make this update seamless for end
users, there are a few things to watch out for and consider, particularly if you
are already an experienced API Tools user.

Manually updating
-----------------

In most cases, you can update your existing API Tools applications to the same
set of dependencies as the skeleton application via Composer:

```bash
$ composer update
```

This will bring in the latest API Tools modules, as well as Laminas components.

However, due to some of the constraints defined in the API Tools 1.3 series, you
will not get all updates, and, in particular, will not be able to start using
Laminas MVC v3 releases immediately. If you wish to do so, you will
need to follow one of two approaches:

- api-tools-admin 1.5 ships with a vendor binary that will update your
  application so that it matches the dependencies of the API Tools 1.4 skeleton.
  You can execute this using:

  ```bash
  $ ./vendor/bin/api-tools-upgrade-to-1.5
  ```

- Alternately, you can manually update your application; to do so, follow
  [the instructions in the api-tools-admin README](https://github.com/laminas-api-tools/api-tools-admin#upgrading-to-v3-laminas-components-from-15).

If you choose either route, please be aware that you may now be missing some
dependencies, based on what Laminas components you were consuming
previously. Please see the [section detailing the smaller API Tools footprint, below](#smaller-footprint).

api-tools-provider
------------------

You no longer need to have the `Laminas\ApiTools\Provider` module listed in your
application modules. The package provides only interfaces, and these are
provided to your application via Composer autoloading already.

Enabling and disabling development mode
---------------------------------------

API Tools 1.4, and, via inheritance, api-tools-admin 1.5, now use a version 3
release of laminas-development-mode. That release modifies the component to no longer
use the MVC &lt;-&gt; Console integration, but instead provides a standalone
vendor binary that has no additional dependencies.

If you are updating from an existing API Tools installation, you will now use
the following commands (issued from the project root):

```bash
# Enable development mode:
$ ./vendor/bin/laminas-development-mode enable
# Disable development mode:
$ ./vendor/bin/laminas-development-mode disable
# Show development mode status:
$ ./vendor/bin/laminas-development-mode status
```

If you create a new project based on API Tools 1.4, the skeleton now provides
composer command aliases for these:

```bash
# Enable development mode:
$ composer development-enable
# Disable development mode:
$ composer development-disable
# Show development mode status:
$ composer development-status
```

Asset management
----------------

In previous versions, we shipped with rwoverdijk/assetmanager by default. In
parallel to developing 1.4, the AssetManager team was working on v3
compatibility. However, the efforts were still in progress when we were testing
API Tools 1.4, and we needed to provide an alternative solution for the interim.

As such, when upgrading, you will need to do the following:

- If you find that rwoverdijk/assetmanager 1.7 has been released (check the
  [releases page](https://github.com/RWOverdijk/AssetManager/releases), run
  `composer require --dev rwoverdijk/assetmanager`.
- Alternately, you may use the new [laminas-api-tools/api-tools-asset-manager](https://github.com/laminas-api-tools/api-tools-asset-manager)
  package: `composer require --dev laminas-api-tools/api-tools-asset-manager`. If you use this
  package, you should likely remove your `vendor/` directory afterwards and
  re-run `composer install` in order for it to copy assets into your public
  tree.

*You will need one or the other of these packages present in order to run the
API Tools admin interface!*

Smaller footprint
-----------------

One huge change for existing API Tools users is that the skeleton no longer
ships with all of Laminas. This was done to mirror the changes in the
[Laminas MVC skeleton application](https://github.com/laminas/laminas-mvc-skeleton)
for the version 3 release, which now ships with the minimal dependencies
required to run a Laminas MVC application.

What this means to you, as an existing API Tools user, is that after an upgrade,
you may find that some Laminas classes you used previously are now
missing.

We suggest the following:

- Search through your application, looking for `use Laminas\\` statements. You can
  do this in your IDE, or using command line tools such as `grep` (e.g., `grep
  '^use Laminas' module` from your project root).
- Once identified, compare your list against the components installed, which you
  can obtain via `composer show` (you can make it even more specific using
  `composer show | grep "laminas-"`).
- For any import statements that do not have a corresponding entry in the
  components installed, add the requirement to your application:

  ```bash
  $ composer require laminas/laminas-{component name}
  ```

  In many cases, Composer may prompt you asking if you want to add the component
  as a module to one or more configuration files. In each case, you can safely
  select `config/modules.config.php`, and tell the installer to remember this
  for any other components installed.

We recommend adding only the dependencies you require, versus the
entire framework. Doing so will reduce both the amount of disk space the
application requires, as well as the memory and resource usage during execution.

Docker
------

The default container in the docker-compose configuration has been renamed from
`dev` to `API Tools`. Additionally, we now only ship one Dockerfile, and
recommend manipulating things like development mode via composer.

These changes will only affect users creating new projects who were accustomed
to the previous configuration.

Vagrant
-------

The vagrant configuration has been completely redone. We have removed the
previous configuration, as it was no longer functioning. In its place, we've
provided a minimal setup that provides Apache 2.4, PHP 7, and Composer, with a
properly configured default virtual host.

The new image is based on ubuntu/xenial64. If you are using VirtualBox as your
provider, you will need:

- Vagrant 1.8.5 or later
- VirtualBox 5.0.26 or later

If you need additional features (databases, etc.), you will need to provide your
own configuration.

PSR-4 by default
----------------

All previous versions of API Tools generated and manipulated new modules using
[PSR-0](http://www.php-fig.org/psr/psr-0/) by default. api-tools-admin 1.1.0
introduced a new configuration, `api-tools-admin.path_spec`, by which you
could specify `psr-4` for the default; however, we never made it the default in
order to keep backwards compatibility.

While it's still not the default when installing api-tools-admin, the
API Tools 1.4 skeleton enables it by default by setting the value in
`config/autoload/global-development.php`. As such, any modules you create in
API Tools 1.4 and up will have a PSR-4 structure.

You may disable it by removing the following line from your
`config/autoload/global-development.php` file:

```php
'path_spec' => ModulePathSpec::PSR_4,
```

(Once removed, you may also remove the import for the `ModulePathSpec` class.)

### Enabling PSR-4 module generation in an existing application

If you have an existing application and want to enable this feature, add the
following configuration to your `config/autoload/global-development.php` file:

```php
'api-tools-admin' => [
    'path_spec' => \Laminas\ApiTools\Admin\Model\ModulePathSpec::PSR_4,
],
```

Short-array syntax by default
-----------------------------

api-tools-configuration has supported short array notation since the first stable
release. However, because we originally were still supporting PHP 5.3, which did
not support short array syntax, we had it disabled by default.

As our minimum required PHP version at this time is 5.6, there's no reason not
to enable it by default in *new* applications (enabling it for existing
applications would result in completely new contents for generated
configuration files!). As such, the API Tools 1.4 skeleton enables this by
default.

You may disable it by removing the following line from your
`config/autoload/global-development.php` file:

```php
'enable_short_array' => true,
```

### Enabling short-array syntax in an existing application

If you have an existing application and want to enable this feature, add the
following configuration to your `config/autoload/global-development.php` file:

```php
'api-tools-configuration' => [
    'enable_short_array' => true,
],
```

::class notation by default
---------------------------

laminas-config's `PhpArray` config writer has supported enumerating class names
using `::class` notation since version 2.6.0, which is now installed by default
with API Tools 1.4. As such, we now enable this feature by default.

You may disable it by removing the following line from your
`config/autoload/global-development.php` file:

```php
'class_name_scalars' => true,
```

### Enabling class notation in an existing application

If you have an existing application and want to enable this feature, add the
following configuration to your `config/autoload/global-development.php` file:

```php
'api-tools-configuration' => [
    'class_name_scalars' => true,
],
```

Enabling Composer-based autoloading
-----------------------------------

Prior to the 1.4 release, in order to enable Composer-based autoloading in your
application, you needed to manually add autoloading rules to your project's
`composer.json` file.

Starting in 1.4, API Tools ships with the laminas/laminas-composer-autoloading
package as a development dependency. This package ships with a vendor binary
that will define autoloading rules in your `composer.json` and then update the
autoloader. You may invoke it as follows:

```bash
$ ./vendor/bin/autoload-module-via-composer <ModuleName>
```

The binary has several other arguments you may provide; invoke it with `--help`,
`-h`, or `--help` to discover these.

Once done, be sure to commit your `composer.json` and `composer.lock` files!

### Adding the utility to an existing application

If you want to add the above feature to an existing API Tools application:

```bash
$ composer require --dev laminas/laminas-composer-autoloading
```
