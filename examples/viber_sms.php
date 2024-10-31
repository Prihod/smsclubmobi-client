<?php

require_once(dirname(__DIR__) . '/vendor/autoload.php');

use SMSClubMobi\Client;
use SMSClubMobi\Exception\RequestException;
use SMSClubMobi\Request\ViberSMSRequest;

$apiKey = 'f8p_o6yycL1yp-Q';
$client = new Client($apiKey);

try {
    $request = (new ViberSMSRequest())
        ->setPhone('380971234567')
        ->setSender('ViberTest')
        ->setMessage('Message text to be sent via Viber')
        ->setSMSSender('SMSTest')
        ->setSMSMessage('Message text to be sent via SMS')
        ->setLink('https://smsclub.mobi/')
        ->setImage('https://smsclub.mobi/img/og_logo.jpg')
        ->setButtonText('Test');

    $response = $client->execute($request);
    print_r($response->toArray());
} catch (RequestException $e) {
    echo $e->getMessage();
}
