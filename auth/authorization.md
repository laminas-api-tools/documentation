Authorization
=============

Authorization is the process by which a system can take a *validated identity* (or lack of
identity) and *determine if that identity has access to a given resource*.  In terms of APIs and
API Tools, the identity that is passed in via the `Authorization` header, which is then validated
during authentication, is then passed into the process that determines if the request/resource can
be accessed by that identity.

With API Tools, the information presented through the `Authorization` header is then converted to
either a `Laminas\ApiTools\MvcAuth\Identity\AuthenticatedIdentity` or `Laminas\ApiTools\MvcAuth\Identity\GuestIdentity`.  The
implementation of authorization uses `Laminas\Permissions\Acl` as a model of an access control list
(ACL).  This list is built in the API Tools Admin UI.  By default, everything is accessible to all
authenticated identities and "guest" identities.  API Tools does not, by default, give you the ability
to create user groups, or assign specific permissions to specific authenticated users.

Authorization happens post-route, but before dispatch of the requested service.  This is what allows
`api-tools-mvc-auth` to be able to determine if a particular identity has access to the requested resource
without having to start the initialization dispatch of any particular controller in the application.

What is unique to API Tools is that with REST resources you have the ability to assign permissions
for each allowed HTTP method for either collections *or* entities.  With RPC services you have the
ability to assign permissions for each allowed HTTP method to the RPC controller.

You can specify the HTTP methods to be use for REST and RPC services selecting the "Authorization"
tab in the service window.

![Authorization Settings REST](/asset/api-tools-documentation/img/auth-authorization-ui-settings-rest.png)

For REST services you can specify the HTTP methods to put under authorization for Entity and
Collection. For RPC services you have only one set of HTTP methods to configure.

![Authorization Settings RPC](/asset/api-tools-documentation/img/auth-authorization-ui-settings-rpc.png) 
