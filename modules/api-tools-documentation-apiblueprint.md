# API Blueprint Documentation Provider for Laminas API Tools

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

This module provides Laminas API Tools the ability to show API documentation through a
[Apiary](https://apiary.io/) documentation.

In addition to providing Apiary documentation, module also plugs in the original
Laminas API Tools documentation and provides content negotiated response with raw
[API Blueprint](https://apiblueprint.org).

## Requirements
  
Please see the [composer.json](https://github.com/laminas-api-tools/api-tools-documentation-apiblueprint/tree/master/composer.json) file.

## Installation

Run the following `composer` command:

```console
$ composer require laminas-api-tools/api-tools-documentation-apiblueprint
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-documentation-apiblueprint": "^1.2"
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
        'Laminas\ApiTools\Documentation\ApiBlueprint',
    .,
    /* ... */
.;
```

> ### laminas-component-installer
>
> If you use [laminas-component-installer](https://github.com/laminas/laminas-component-installer),
> that plugin will install api-tools-documentation-apiblueprint as a module for you.

## Usage

Apiary documentation can be found on `/api-tools/blueprint/:api` uri and is
accessible from the Laminas API Tools welcome page.

## Querying API Blueprint

When raw API Blueprint is needed, request can be done via content negotiation.
Target uri is `/api-tools/blueprint/:api` and Accept header is
`text/vnd.apiblueprint+markdown`.

To learn more about API Blueprint language, please check its
[specification](https://github.com/apiaryio/api-blueprint/blob/master/API%20Blueprint%20Specification.md).
