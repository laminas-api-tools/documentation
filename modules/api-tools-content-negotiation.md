# Laminas Content Negotiation

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

`api-tools-content-negotiation` is a module for automating content negotiation tasks within a Laminas
Framework application.

The following features are provided

- Mapping `Accept` header media types to specific view model types, and
  automatically casting controller results to those view model types.
- Defining `Accept` header media type whitelists; requests with `Accept` media types
  that fall outside the whitelist will be immediately rejected with a `406 Not
  Acceptable` response.
- Defining `Content-Type` header media type whitelists; requests sending content
  bodies with `Content-Type` media types that fall outside the whitelist will be
  immediately rejected with a `415 Unsupported Media Type` response.

## Requirements

Please see the [composer.json](https://github.com/laminas-api-tools/api-tools-content-negotiation/tree/master/composer.json) file.

## Installation

Run the following `composer` command:

```console
$ composer require laminas-api-tools/api-tools-content-negotiation
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-content-negotiation": "^1.2"
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
        'Laminas\ApiTools\ContentNegotiation',
    ],
    /* ... */
];
```

> ### laminas-component-installer
>
> If you use [laminas-component-installer](https://github.com/laminas/laminas-component-installer),
> that plugin will install api-tools-content-negotiation as a module for you.

## Configuration

### User Configuration

The top-level configuration key for user configuration of this module is `api-tools-content-negotiation`.

#### Key: `controllers`

The `controllers` key is utilized for mapping a content negotiation strategy to a particular
controller service name (from the top-level `controllers` section).  The value portion
of the controller array can either be a _named selector_ (see `selectors` below), or a
selector definition.

A selector definition consists of an array with the key of the array being the name of a view model,
and the value of it being an indexed array of media types that, when matched, will select that view
model.

Example:

```php
'controllers' => [
    // Named selector:
    'Application\Controller\HelloWorld1' => 'Json',

    // Selector definition:
    'Application\Controller\HelloWorld2' => [
        'Laminas\ApiTools\ContentNegotiation\JsonModel' => [
            'application/json',
            'application/*+json',
        ],
    ],
],
```

#### Key: `selectors`

The `selectors` key is utilized to create named selector definitions for reuse between many different
controllers.  The key part of the selectors array will be a name used to correlate the selector
definition (which uses the format described in the [controllers](#key-controllers) key).

Example:

```php
'selectors' => [
    'Json' => [
        'Laminas\ApiTools\ContentNegotiation\JsonModel' => [
            'application/json',
            'application/*+json',
        ],
    ],
],
```

A selector can contain multiple view models, each associated with different media types, allowing
you to provide multiple representations. As an example, the following selector would allow a given
controller to return either JSON or HTML output:

```php
'selectors' => [
    'HTML-Json' => [
        'Laminas\ApiTools\ContentNegotiation\JsonModel' => [
            'application/json',
            'application/*+json',
        ],
        'Laminas\ApiTools\ContentNegotiation\ViewModel' => [
            'text/html',
        ],
    ],
],
```

#### Key: `accept_whitelist`

The `accept_whitelist` key is utilized to instruct the content negotiation module which media types
are acceptable for a given controller service name. When a controller service name is configured
in this key, along with an indexed array of matching media types, only media types that match
the `Accept` header of a given request will be allowed to be dispatched.  Unmatched media types
will receive a `406 Cannot honor Accept type specified` response.

The value of each controller service name key can either be a string or an array of strings.

Example:

```php
'accept_whitelist' => [
    'Application\\Controller\\HelloApiController' => [
        'application/vnd.application-hello+json',
        'application/hal+json',
        'application/json',
    ],
],
```

#### Key: `content_type_whitelist`

The `content_type_whitelist` key is utilized to instruct the content negotiation module which media
types are valid for the `Content-Type` of a request.  When a controller service name is
configured in this key, along with an indexed array of matching media types, only media types
that match the `Content-Type` header of a given request will be allowed to be dispatched. Unmatched
media types will receive a `415 Invalid content-type specified` response.

The value of each controller service name key can either be a string or an array of strings.

Example:

```php
'content_type_whitelist' => [
    'Application\\Controller\\HelloWorldController' => [
        'application/vnd.application-hello-world+json',
        'application/json',
    ],
],
```

#### Key: `x_http_method_override_enabled`

- Since 1.3.0

This boolean flag determines whether or not the `HttpMethodOverrideListener`
will be enabled by default.

#### Key: `http_override_methods`

- Since 1.3.0

The `http_override_methods` key is utilized to provide the
`HttpMethodOverrideListener` with a map of allowed override methods for a given
HTTP method, as specified via the `X-HTTP-Method-Override` header. Essentially,
the values are:

```php
'Incoming HTTP request method' => $arrayOfAllowedOverrideMethods,
```

As an example, if you want to allow the `X-HTTP-Method-Override` header to allow
overriding HTTP `GET` requests with an alternate method, you might define this
as follows:

```php
'x_http_method_override_enabled' => true,
'http_override_methods' => [
    'GET' => [
        'HEAD',
        'POST',
        'PUT',
        'DELETE',
        'PATCH',
    ],
];
```

Then, to make a request, you could do the following:

```http
GET /foo HTTP/1.1
Host: example.com
X-HTTP-Method-Override: PATCH

some=content&more=content
```

The above would then be interpreted as a `PATCH` request. If the same request
were made via HTTP `POST`, or if a `GET` request were made with an override
value of `OPTIONS`, the listener would raise a problem, as, in the former case,
no maps are defined for `POST`, and, in the latter, `OPTIONS` is not in the map
for `GET`.

### System Configuration

The following configuration is provided in `config/module.config.php` to enable the module to
function:

```php
'filters' => [
    'aliases'   => [
        'Laminas\Filter\File\RenameUpload' => 'filerenameupload',
    ],
    'factories' => [
        'filerenameupload' => Factory\RenameUploadFilterFactory::class,
    ],
],

'validators' => [
    'aliases'   => [
        'Laminas\Validator\File\UploadFile' => 'fileuploadfile',
    ],
    'factories' => [
        'fileuploadfile' => Factory\UploadFileValidatorFactory::class,
    ],
],

'service_manager' => [
    'factories' => [
        ContentTypeListener::class        => InvokableFactory::class,
        AcceptListener::class             => Factory\AcceptListenerFactory::class,
        AcceptFilterListener::class       => Factory\AcceptFilterListenerFactory::class,
        ContentTypeFilterListener::class  => Factory\ContentTypeFilterListenerFactory::class,
        ContentNegotiationOptions::class  => Factory\ContentNegotiationOptionsFactory::class,
        HttpMethodOverrideListener::class => Factory\HttpMethodOverrideListenerFactory::class,
    ],
],

'controller_plugins' => [
    'aliases' => [
        'routeParam'  => ControllerPlugin\RouteParam::class,
        'queryParam'  => ControllerPlugin\QueryParam::class,
        'bodyParam'   => ControllerPlugin\BodyParam::class,
        'routeParams' => ControllerPlugin\RouteParams::class,
        'queryParams' => ControllerPlugin\QueryParams::class,
        'bodyParams'  => ControllerPlugin\BodyParams::class,
    ],
    'factories' => [
        ControllerPlugin\RouteParam::class  => InvokableFactory::class,
        ControllerPlugin\QueryParam::class  => InvokableFactory::class,
        ControllerPlugin\BodyParam::class   => InvokableFactory::class,
        ControllerPlugin\RouteParams::class => InvokableFactory::class,
        ControllerPlugin\QueryParams::class => InvokableFactory::class,
        ControllerPlugin\BodyParams::class  => InvokableFactory::class,
    ],
],
```

## Laminas Events

### Listeners

#### Laminas\ApiTools\ContentNegotiation\AcceptListener

This listener is attached to the `MvcEvent::EVENT_DISPATCH` event with priority `-10`.  It is
responsible for performing the actual selection and casting of a controller's view model based on
the content negotiation configuration.

#### Laminas\ApiTools\ContentNegotiation\ContentTypeListener

This listener is attached to the `MvcEvent::EVENT_ROUTE` event with a priority of `-625`. It is
responsible for examining the `Content-Type` header in order to determine how the content body
should be deserialized. Values are then persisted inside of a `ParameterDataContainer` which is
stored in the `LaminasContentNegotiationParameterData` key of the `MvcEvent` object.

#### Laminas\ApiTools\ContentNegotiation\AcceptFilterListener

This listener is attached to the `MvcEvent::EVENT_ROUTE` event with a priority of `-625`. It is
responsible for ensuring the controller selected by routing is configured to respond to the specific
media type in the current request's `Accept` header.  If it cannot, it will short-circuit the MVC
dispatch process by returning a `406 Cannot honor Accept type specified` response.

#### Laminas\ApiTools\ContentNegotiation\ContentTypeFilterListener

This listener is attached to the `MvcEvent::EVENT_ROUTE` event with a priority of `-625`. It is
responsible for ensuring the route matched controller can accept content in the request body
specified by the media type in the current request's `Content-Type` header. If it cannot, it will
short-circuit the MVC dispatch process by returning a `415 Invalid content-type specified` response.

#### Laminas\ApiTools\ContentNegotiation\HttpMethodOverrideListener

- Since 1.3.0

This listener is attached to the `MvcEvent::EVENT_ROUTE` event with a priority
of `-40`, but only if the `x_http_method_override_enabled` configuration flag
was toggle on. It is responsible for checking if an `X-HTTP-Method-Override`
header is present, and, if so, if it contains a value in the set allowed for the
current HTTP request method invoked. If so, it resets the HTTP request method to
the header value.

## Laminas Services

### Controller Plugins

#### Laminas\ApiTools\ContentNegotiation\ControllerPlugin\RouteParam (a.k.a "routeParam")

A controller plugin (`Laminas\Mvc\Controller\AbstractActionController` callable) that will return a
single parameter with a particular name from the route match.

```php
use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->routeParam('id', 'someDefaultValue');
    }
}
```

#### Laminas\ApiTools\ContentNegotiation\ControllerPlugin\QueryParam (a.k.a "queryParam")

A controller plugin (`Laminas\Mvc\Controller\AbstractActionController` callable) that will return a
single parameter from the current request query string.

```php
use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->queryParam('foo', 'someDefaultValue');
    }
}
```

#### Laminas\ApiTools\ContentNegotiation\ControllerPlugin\BodyParam (a.k.a "bodyParam")

A controller plugin (`Laminas\Mvc\Controller\AbstractActionController` callable) that will return a
single parameter from the content-negotiated content body.


```php
use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->bodyParam('foo', 'someDefaultValue');
    }
}
```

#### Laminas\ApiTools\ContentNegotiation\ControllerPlugin\RouteParams (a.k.a "routeParams")

A controller plugin (`Laminas\Mvc\Controller\AbstractActionController` callable) that will return a
all the route parameters.

```php
use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->routeParams()
    }
}
```

#### Laminas\ApiTools\ContentNegotiation\ControllerPlugin\QueryParams (a.k.a "queryParams")

A controller plugin (`Laminas\Mvc\Controller\AbstractActionController` callable) that will return a
all the query parameters.

```php
use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->queryParams()
    }
}
```

#### Laminas\ApiTools\ContentNegotiation\ControllerPlugin\BodyParams (a.k.a "bodyParams")

A controller plugin (`Laminas\Mvc\Controller\AbstractActionController` callable) that will return a
all the content-negotiated body parameters.

```php
use Laminas\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return $this->bodyParams()
    }
}
```
