<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use SMSClubMobi\Client;
use SMSClubMobi\Exception\RequestException;
use SMSClubMobi\Request\StatusViberRequest;

$apiKey = 'f8p_o6yycL1yp-Q';
$client = new Client($apiKey);

try {
    $request = (new StatusViberRequest())
        ->setMessageId(1234)
        ->setMessageId(1235)
        ->setMessageId(1236);

    $response = $client->execute($request);

    if ($response->isSuccess()) {
        echo "Response to array:\n";
        print_r($response->toArray());
        echo "Response get data:\n";
        print_r($response->getData());
        echo "Response get info:\n";
        print_r($response->getInfo());
    } else {
        echo "Response Error: {$response->getError()}";
    }
} catch (RequestException $e) {
    echo "Exception: {$e->getMessage()}\n";
    print_r($e->request->toArray());
}