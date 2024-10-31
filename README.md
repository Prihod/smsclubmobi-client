# smsclubmobi-client

This is client library for the [smsclub.mobi](https://smsclub.mobi) API.

## 1. Prerequisites

* PHP 7.4 or later

## 2. Installation

The smsclubmobi-client client can be installed using Composer by running the following command:

```sh
composer require prihod/smsclubmobi-client
```

## 3. Initialization

Create Client object using the following code:

```php
<?php

use SMSClubMobi\Client;

require_once __DIR__ . '/vendor/autoload.php';

$apiKey = 'f8p_o6yycL1yp-Q';
$client = new Client($apiKey);
```
## 4. API Requests

### 4.1. Request get balance

```php
use SMSClubMobi\Request\BalanceRequest;
use SMSClubMobi\Exception\RequestException;

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
```
### 4.2. Request to send SMS

```php
use SMSClubMobi\Request\SMSRequest;

 $request = (new SMSRequest())
        ->setPhone('380971234567')
        ->setSender('SMSSender')
        ->setMessage('Message text to be sent via SMS');

$response = $client->execute($request);
...
```

### 4.3. Multiple SMS sending request

```php
use SMSClubMobi\Request\SMSRequest;

 $request = (new SMSRequest())
        ->setPhone('380971234567')
        ->setPhone('380971234568')
        ->setSender('SMSSender')
        ->setMessage('Message text to be sent via SMS');

$response = $client->execute($requests);
...
```

### 4.4. Request status SMS message
```php
use SMSClubMobi\Request\StatusSMSRequest;

 $request = (new StatusSMSRequest())
        ->setSMSId(1234)
        ->setSMSId(1235);

$response = $client->execute($request);
...
```


#### 4.5.  Request to send a message to Viber

```php
use SMSClubMobi\Request\ViberRequest;

  $request = (new ViberRequest())
        ->setPhone('380971234567')
        ->setSender('ViberTest')
        ->setMessage('Message text to be sent via Viber')
        ->setLink('https://smsclub.mobi/')
        ->setImage('https://smsclub.mobi/img/og_logo.jpg')
        ->setButtonText('Test');

$response = $client->execute($request);
...
```


#### 4.6.  Request to send a message to Viber with SMS

```php
use SMSClubMobi\Request\ViberSMSRequest;

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
...
```

#### 4.7. Request to send a message to Viber2Way

```php
use SMSClubMobi\Request\Viber2WayRequest;

$request = (new Viber2WayRequest())
        ->setPhone('380971234567')
        ->setSender('ViberTest')
        ->setMessage('Message text to be sent via Viber');

$response = $client->execute($request);
...
```

#### 4.7. Request status Viber message

```php
use SMSClubMobi\Request\StatusViberRequest;

    $request = (new StatusViberRequest())
        ->setMessageId(1234)
        ->setMessageId(1235)
        ->setMessageId(1236);

$response = $client->execute($request);
...
```

## 5. Links

* API [docs](https://smsclub.mobi/api/)
