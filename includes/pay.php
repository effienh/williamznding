<?php
require __DIR__ . '/../vendor/autoload.php';
$mollie = new \Mollie\Api\MollieApiClient();
$mollie->setApiKey("test_apB8t3vNyk52SCfndCEq7caRneGJpw");

$method = $mollie->methods->get(\Mollie\Api\Types\PaymentMethod::IDEAL, ["include" => "issuers"]);

$payment = $mollie->payments->create([
    "amount" => [
        "currency" => "EUR",
        "value" => "10.00"
    ],
    "description" => "My first API payment",
    "redirectUrl" => "https://webshop.example.org/order/12345/",
    "webhookUrl"  => "https://webshop.example.org/mollie-webhook/",
]);

 ?>
