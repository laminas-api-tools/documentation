Introduction
============

Content validation within Laminas API Tools is the process of taking incoming data and determining if it is
valid. If it is not then an [API Problem response](/api-primer/error-reporting.md) is returned
containing details on the validation failures.

For each service, API Tools allows you to configure a fieldset that is to be used when data is
passed to the service. To accomplish this, API Tools uses the
[api-tools-content-validation](https://github.com/laminas-api-tools/api-tools-content-validation) module to create 
[Laminas input filters](https://docs.laminas.dev/laminas-inputfilter/intro/),
then executes the input filter associated with a service when data is submitted.

> ## Note: Content Validation Request Methods
>
> Content Validation currently only works for `POST`, `PATCH`, and `PUT` requests. If you need to
> validate query string parameters, you will need to write your own logic for those tasks.

_Input filters_ accomplish the jobs of filtering (via the
[Laminas\Filter](https://docs.laminas.dev/laminas-filter/intro/) component) and
validating (via the [Laminas\Validator](https://docs.laminas.dev/laminas-validator/intro/)
component). To quote the Laminas manual on the purpose of input filters:

> The `Laminas\InputFilter` component can be used to filter and validate generic sets of input data.
> For instance, you could use it to filter `$_GET` or `$_POST` values, CLI arguments, etc.

An _input filter_ is composed of one or more _input_ objects (or even other _input filters_!). Each
input object represents a named incoming _field_ which contains information on how to validate it:

- Is the value required?
- If required, is it allowed to be empty?
- If it is allowed to be empty, should validators be executed anyways?
- What normalization filters should execute for this value?
- What validators should the normalized value be passed to?
- Should the input return validation error messages from the aggregate validators, or present a
  single error message when invalid?

The input filter iterates over each input (or input filter) it composes, passing it the
corresponding field value; only if all inputs validate does it pass validation; if any input is invalid,
the entire input filter is considered invalid.

Within the API Tools Admin UI, input filters are defined in the "Fields" tab of a service. This UI
allows you to describe what the incoming data fieldset should look like, what options are configured
for each field, which filters the field will utilize, and which validators it will execute. The
description is saved as an input filter specification which can be consumed by
`Laminas\InputFilter\Factory` in order to return a concrete `Laminas\InputFilter\InputFilter` instance -
which is then used for validating incoming data.
