# Swagger Documentation Provider for Laminas API Tools

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
[Swagger UI](http://swagger.io/).

The Swagger UI is immediately accessible after enabling this module at the URI path `/api-tools/swagger`.

In addition to providing the HTML UI, this module also plugs into the main Laminas API Tools documentation
resource (at the path `/api-tools/documentation`) in order to allow returning a documentation
payload in the `application/vnd.swagger+json` media type; this resource is what feeds the Swagger
UI. You can access this representation by passing the media type `application/vnd.swagger+json` for
the `Accept` header via the path `/api-tools/documentation/:module/:service`.

## Requirements
  
Please see the [composer.json](https://github.com/laminas-api-tools/api-tools-documentation-swagger/tree/master/composer.json) file.

## Installation

Run the following `composer` command:

```console
$ composer require laminas-api-tools/api-tools-documentation-swagger
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-documentation-swagger": "^1.2"
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
        'Laminas\ApiTools\Documentation\Swagger',
    ],
    /* ... */
];
```

> ### laminas-component-installer
>
> If you use [laminas-component-installer](https://github.com/laminas/laminas-component-installer),
> that plugin will install api-tools-documentation-swagger as a module for you.

## Routes

### /api-tools/swagger

Shows the Swagger UI JavaScript application.

### Assets: `/api-tools-documentation-swagger/`

Various CSS, images, and JavaScript libraries required to deliver the Swagger UI client
application.

## Configuration

### System Configuration

The following is required to ensure the module works within a Laminas and/or Laminas API Tools-enabled
application:

```php
namespace Laminas\ApiTools\Documentation\Swagger;

return [
    'router' => [
        'routes' => [
            'api-tools' => [
                'child_routes' => [
                    'swagger' => [
                        'type' => 'segment',
                        'options' => [
                            'route'    => '/swagger',
                            'defaults' => [
                                'controller' => SwaggerUi::class,
                                'action'     => 'list',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'api' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/:api',
                                    'defaults' => [
                                        'action' => 'show',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            SwaggerViewStrategy::class => SwaggerViewStrategyFactory::class,
        ],
    ],

    'controllers' => [
        'factories' => [
            SwaggerUi::class => SwaggerUiControllerFactory::class,
        ],
    ],

    'view_manager' => [
        'template_path_stack' => [
            'api-tools-documentation-swagger' => __DIR__ . '/../view',
        ],
    ],

    'asset_manager' => [
        'resolver_configs' => [
            'paths' => [
                __DIR__ . '/../asset',
            ],
        ],
    ],

    'api-tools-content-negotiation' => [
        'accept_whitelist' => [
            'Laminas\ApiTools\Documentation\Controller' => [
                0 => 'application/vnd.swagger+json',
            ],
        ],
        'selectors' => [
            'Documentation' => [
                ViewModel::class => [
                    'application/vnd.swagger+json',
                ],
            ],
        ],
    ],
];
```

## Laminas Events

### Listeners

#### Laminas\ApiTools\Documentation\Swagger\Module

This listener is attached to the `MvcEvent::EVENT_RENDER` event at priority `100`.  Its purpose is
to conditionally attach a view strategy to the view system in cases where the controller response is
a `Laminas\ApiTools\Documentation\Swagger\ViewModel` view model (likely selected as the
content-negotiated view model based off of `Accept` media types).

## Laminas Services

### View Models

#### Laminas\ApiTools\Documentation\Swagger\ViewModel

This view model is responsible for translating the available `Laminas\ApiTools\Documentation` models
into Swagger-specific models, and further casting them to arrays for later rendering as JSON.
