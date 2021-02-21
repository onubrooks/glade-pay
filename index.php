<?php

require_once  'vendor/autoload.php';

use onubrooks\GladePay\GladePay;

$glade = new GladePay();

$amount = 30000;
$user_data = '
{
    "firstname":"John",
    "lastname":"Wick",
    "email":"wick@gladeuniverse.com",
    "ip":"192.168.10.5",
    "fingerprint": "cccvxbxbxb"
}
';
$business_name = "Happy Glade Customer";
$user = json_decode($user_data);

$transaction = $glade->bankTransfer($amount, $user, $business_name);

$verification = $glade->verify($transaction["txnRef"]);

echo var_dump($transaction);
