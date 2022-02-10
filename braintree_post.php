<?php
/**
 * Created by PhpStorm.
 * User: Goat
 * Date: 2/10/2022
 * Time: 12:08 AM
 */

require_once './lib/Braintree.php';

$config = new Braintree\Configuration([
    'environment' => 'sandbox',
    'merchantId' => '2yd43b8kxcq676nb',
    'publicKey' => 'k87sq59mwz936t4h',
    'privateKey' => '08a2040738d37cefec32a3dc318f5156'
]);
$gateway = new Braintree\Gateway($config);
$today = new Datetime;

$transactions = $gateway->transaction()->search([
    Braintree\TransactionSearch::settledAt()->greaterThanOrEqualTo($today->modify('-1 day'))
]);

$result = [];
foreach($transactions as $transaction) {

    $createdAt = $transaction->createdAt->format('d/m/Y H:i:s');
    $status = $transaction->status;
    $currencyIsoCode = $transaction->currencyIsoCode;
    $firstName = $transaction->customer['firstName'];
    $lastName = $transaction->customer['lastName'];
    $cardType = $transaction->creditCard['cardType'];
    $orderId = $transaction->orderId;
    $authorized = $transaction->statusHistory[0]->amount;
    $settled = $transaction->statusHistory[2]->amount;
    $serviceFeeAmount = $transaction->serviceFeeAmount;

    $merchantAccountId = $transaction->merchantAccountId;

    $ary = array(
        'createdAt' => $createdAt,
        'status' => $status,
        'currencyIsoCode' => $currencyIsoCode,
        'firstName' => $firstName,
        'lastName' => $lastName,
        'cardType' => $cardType,
        'orderId' => $orderId,
        'authorized' => $authorized,
        'settled' => $settled,
        'serviceFeeAmount' => $serviceFeeAmount,
        'net' => 0,
    );
    $result[] = $ary;
}

echo json_encode($result);
//exit;