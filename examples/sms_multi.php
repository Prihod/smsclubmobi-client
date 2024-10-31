<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use SMSClubMobi\Client;
use SMSClubMobi\Exception\RequestException;
use SMSClubMobi\Request\SMSRequest;

$apiKey = 'f8p_o6yycL1yp-Q';
$client = new Client($apiKey);

try {
    $request = (new SMSRequest())
        ->setPhone('380971234567')
        ->setPhone('380971234568')
        ->setPhone('380971234569')
        ->setSender('SMSSender')
        ->setMessage('Message text to be sent via SMS');

    $response = $client->execute($request);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
}
