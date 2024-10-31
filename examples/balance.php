<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use SMSClubMobi\Client;
use SMSClubMobi\Exception\RequestException;
use SMSClubMobi\Request\BalanceRequest;

$apiKey = 'f8p_o6yycL1yp-Q';
$client = new Client($apiKey);

try {
    $request = new BalanceRequest();
    $response = $client->execute($request);

    if ($response->isSuccess()) {
        echo "Response to array:\n";
        print_r($response->toArray());
        echo "Response get data:\n";
        print_r($response->getData());
        if ($response->hasErrors()) {
            echo "Response get errors for parts:\n";
            print_r($response->getErrors());
        }
    } else {
        echo "Response Error: {$response->getError()}";
    }
} catch (RequestException $e) {
    echo "Exception: {$e->getMessage()}\n";
    print_r($e->request->toArray());
}