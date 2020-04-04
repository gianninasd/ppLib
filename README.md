[![Latest Stable Version](https://poser.pugx.org/gianninasd/pplib/v/stable)](https://packagist.org/packages/gianninasd/pplib) 
[![Latest Unstable Version](https://poser.pugx.org/gianninasd/pplib/v/unstable)](https://packagist.org/packages/gianninasd/pplib) 
[![Total Downloads](https://poser.pugx.org/gianninasd/pplib/downloads)](https://packagist.org/packages/gianninasd/pplib)

PPLib
================
PHP Library for payment processing integration

## Pre-requisites
* Install Composer
* Install PHP 7.x
* Download the latest codeception phar distributable from https://codeception.com/install
  * Place the binary distribution in same folder as the value set for extension_dir in the php.ini

## Getting started
* Clone the repo from github to your local machine
* Install all dependencies using composer
  * Run `composer install` and a _vendor_ folder will be generated

## Automated Tests
* Update the values of the global variables in the `tests/unit/_bootstrap.php` file 
* To execute the unit tests, from a console run: `php <path to>/codecept.phar run unit`

## Usage
Below is some sample code on how to use the various classes to prepare, send and process a payment.

```php
// will be your own class representing the full billing data
$member = new class {
  ...
  public $firstName = "John";
  public $lastName = "Doe";
  ...
};

$parser = new PaysafeParser();

// create the JSON body first
$obj = $parser->parseRequest( $uuid, $token, $member, $amt );
$body = json_encode($obj, JSON_NUMERIC_CHECK);

$req = new PaymentRequest();
$req->id = "rick@sdf3.com";
$req->uuid = uniqid("", true);
$req->body = $body;

// Send the request to the remote third party service provider
$ps = new PaysafePaymentService( "https://somedomain.com/somepath", "some authentication token" );
$resp = $ps->process( $req );

// Process the response
$jsonResponse = $parser->parseResponse( $resp );
echo( $jsonResponse );
```

## References
* https://getcomposer.org/
* https://packagist.org/
* https://poser.pugx.org/
* http://www.darwinbiler.com/creating-composer-package-library/
* https://blog.jgrossi.com/2013/creating-your-first-composer-packagist-package/
* https://www.w3resource.com/php/composer/create-publish-and-use-your-first-composer-package.php - describes how to submit to packagist
* https://developer.mozilla.org/en-US/docs/Web/HTTP/Status