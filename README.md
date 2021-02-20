# GladePay

A small PHP library/package that uses the Glade API to make payments using the bank transfer option.

## Getting Started

You need to have PHP and composer installed on your machine to use this package. The latest versions of both are recommended.

## Installation

You can clone this project and include the class in your project or add the dependency in your `composer.json` file as in the example below:

```json
{
    "require": {
        "onubrooks/glade-pay": "^1.0.0"
    }
}
```

Or run the following command in your project:

 `composer require onubrooks/glade-pay`

## Usage

Instantiate the GladePay Class:

```php
require_once __DIR__ . '/vendor/autoload.php';

use onubrooks\GladePay\GladePay;

$glade = new GladePay();

```

### Pay Using Bank Transfer

```php

$amount = 30000;
$user_data = '
{
    "firstname":"John",
    "lastname":"Wick",
    "email":"wick@gladeuniverse.com",
    "ip":"192.168.10.5",
    "fingerprint": "cccvxbxbxbc"
}
';
$business_name = "Happy Glade Customer";
$user = json_decode($user_data);

//Initiate a bank transfer Transaction, country and currency are optional and defaults to 'NG' and 'NGN'
// user and business_name are also optional
$transaction = $glade->bankTransfer($amount, $user, $business_name);

//After the transaction is completed, verify to confirm final status.
$verification = $glade->verify("GP008928746Y");

```

## Return Values

All methods return an array.

Find other repos by the same author at his [github profile](https://github.com/onubrooks).
