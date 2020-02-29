Authentication & Authorization
==============================

Laminas API Tools takes a lightweight, layered, and extensible approach to solving both problems of
authentication and authorization.  In API Tools, this infrastructure is already in place and ready to be
configured to use, or for more advanced use cases, to be extended.  Many of these features can be
explored through the API Tools user interface.

API Tools resources define allowed HTTP methods on the entity and the collection for each resource.
While much of the terminology might be similar, authentication and authorization **ARE NOT** the same
as the set of allowed HTTP methods. These methods are labeled as _allowed_ in the sense that a particular
REST or RPC service can respond to that method regardless of what authentication/authorization is
configured, or which identity is present on any given request to that particular service; see
[the section on HTTP negotiation](/api-primer/http-negotiation.md) for more information.  Resource based
authorization may alter the availability of configured allowed HTTP methods because the configuration on
the resource is resource configuration and not authentication or authorization configuration.

Authentication and Authorization is handled by [laminas-api-tools/api-tools-mvc-auth](https://github.com/laminas-api-tools/api-tools-mvc-auth),
which contains functionality for both generic needs and API-specific needs via adapters. While
api-tools-mvc-auth is a part of API Tools, it enables a wealth of Authentication and Authorization options
outside the scope of this documentation. For a more generic understanding of api-tools-mvc-auth,
[see its documentation](https://github.com/laminas-api-tools/api-tools-mvc-auth).
