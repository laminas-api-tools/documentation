Laminas API Tools Welcome Screen
========================
Welcome page for the web interface of the [Laminas API Tools](http://www.api-tools.getlaminas.org) project.


Installation
------------

Run the following `composer` command:

```console
$ composer require laminas-api-tools/api-tools-welcome
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-welcome": "^1.1"
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
        'Laminas\ApiTools\Welcome',
    ],
    /* ... */
];
```

> ### laminas-component-installer
>
> If you use [laminas-component-installer](https://github.com/laminas/laminas-component-installer),
> that plugin will install api-tools-welcome as a module for you.
