# gringotts-sdk
Evaneos assets storage SDK

## Installation 

In order to be up and running with the SDK you'll need to require it via composer

`composer require evaneos/gringotts-sdk`

and then create a new instance with the URL of your Gringotts instance :

```php
$gringotts = new GringottsClient('http://your-gringotts-instance.com');
```

## Storing an asset

Gringotts SDK allows to store assets through different forms : String, [PHP resource](http://php.net/manual/en/language.types.resource.php) or [PSR StreamInterface](http://www.php-fig.org/psr/psr-7/#3-4-psr-http-message-streaminterface) and will respond with the asset id.

```php
$gringotts = new GringottsClient('http://your-gringotts-instance.com');

$assetId = $gringotts->store('Some data as string');

```

## Retrieving an asset

You can retrieve a previously stored asset using the id provided while storing it. Gringotts SDK will return a stream implementing [PSR StreamInterface](http://www.php-fig.org/psr/psr-7/#3-4-psr-http-message-streaminterface).

```php
$gringotts = new GringottsClient('http://your-gringotts-instance.com');

$asset = $gringotts->get('871CDC92-A75B-43E7-A38E-FC22AA5450CA');
```


