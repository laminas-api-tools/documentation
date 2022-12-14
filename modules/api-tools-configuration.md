# Laminas Configuration

> ## 🇷🇺 Русским гражданам
> 
> Мы, участники Laminas, родились и живем в разных странах. У многих из нас есть друзья, родственники и коллеги как в России, так и в Украине. Некоторые из нас родились в России. Некоторые из нас живут в России. У некоторых бабушки и дедушки сражались с фашистами во Второй мировой войне. Здесь никто не поддерживает фашизм.
> 
> У одного из нас есть украинская родственница, которая спаслась из дома вместе с сыном. Поезд задержался из-за бомбежки на дороге впереди. У нас есть друзья, которые прячутся в бомбоубежищах. Мы с тревогой ждем весточки от них после воздушных налетов, которые беспорядочно наносят удары и попадают по больницам, школам, детским садам и домам. Мы не берем это из каких-либо СМИ. Мы наблюдаем это напрямую.
> 
> Вы доверяете нам достаточно, чтоб использовать наши программы, и мы просим вас довериться нам вновь. Мы нуждаемся в помощи. Выходите и протестуйте против этой бесполезной войны. Остановите кровопролитие. Скажите "Нет войне!"
> 
> ## 🇺🇸 To Citizens of Russia
> 
> We at Laminas come from all over the world. Many of us have friends, family and colleagues in both Russia and Ukraine. Some of us were born in Russia. Some of us currently live in Russia. Some have grandparents who fought Nazis in World War II. Nobody here supports fascism.
> 
> One team member has a Ukrainian relative who fled her home with her son. The train was delayed due to bombing on the road ahead. We have friends who are hiding in bomb shelters. We anxiously follow up on them after the air raids, which indiscriminately fire at hospitals, schools, kindergartens and houses. We're not taking this from any media. These are our actual experiences.
> 
> You trust us enough to use our software. We ask that you trust us to say the truth on this. We need your help. Go out and protest this unnecessary war. Stop the bloodshed. Say "stop the war!"

## Introduction

api-tools-configuration is a module that provides configuration services that provide for the
runtime management and modification of Laminas application configuration files.

## Requirements
  
Please see the [composer.json](https://github.com/laminas-api-tools/api-tools-configuration/tree/master/composer.json) file.

## Installation

Run the following `composer` command:

```console
$ composer require laminas-api-tools/api-tools-configuration
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-configuration": "^1.2"
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
        'Laminas\ApiTools\Configuration',
    ],
    /* ... */
];
```

> ### laminas-component-installer
>
> If you use [laminas-component-installer](https://github.com/laminas/laminas-component-installer),
> that plugin will install api-tools-configuration as a module for you.

## Configuration

### User Configuration

The top-level configuration key for user configuration of this module is `api-tools-configuration`.

```php
'api-tools-configuration' => [
    'config_file' => 'config/autoload/development.php',
    'enable_short_array' => false,
    'class_name_scalars' => false,
],
```

#### Key: `enable_short_array`

Set this value to a boolean `true` if you want to use PHP 5.4's square bracket (aka "short") array
syntax.

#### Key: `class_name_scalars`

- Since 1.2.1

Set this value to a boolean `true` if you want to use PHP 5.5's class name scalars (`::class` notation).

### Laminas Events

There are no events or listeners.

### Laminas Services

#### Laminas\ApiTools\Configuration\ConfigWriter

`Laminas\ApiTools\Configuration\ConfigWriter` is by default an instance of `Laminas\Config\Writer\PhpArray`.  This
service serves the purpose of providing the necessary dependencies for `ConfigResource` and
`ConfigResourceFactory`.

#### Laminas\ApiTools\Configuration\ConfigResource

`Laminas\ApiTools\Configuration\ConfigResource` service is used for modifying an existing configuration files with
methods such as `patch()` and `replace()`.  The service returned by the service manager is bound to
the file specified in the `config_file` key.

#### Laminas\ApiTools\Configuration\ConfigResourceFactory

`Laminas\ApiTools\Configuration\ConfigResourceFactory` is a factory service that provides consumers with the
ability to create `Laminas\ApiTools\Configuration\ConfigResource` objects, with dependencies injected for specific
config files (not the one listed in the `module.config.php`.

#### Laminas\ApiTools\Configuration\ModuleUtils

`Laminas\ApiTools\Configuration\ModuleUtils` is a service that consumes the `ModuleManager` and provides the
ability to traverse modules to find their path on disk as well as the path to their configuration
files.
