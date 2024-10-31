<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use SMSClubMobi\Client;
use SMSClubMobi\Exception\RequestException;
use SMSClubMobi\Request\Viber2WayRequest;

$apiKey = 'f8p_o6yycL1yp-Q';
$client = new Client($apiKey);

try {
    $request = (new Viber2WayRequest())
        ->setPhone('380971234567')
        ->setSender('ViberTest')
        ->setMessage('Message text to be sent via Viber');

    $response = $client->execute($request);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
}
