<?php

use SMSClubMobi\Client;
use SMSClubMobi\Request\BalanceRequest;
use SMSClubMobi\Request\SMSRequest;
use SMSClubMobi\Request\StatusSMSRequest;
use SMSClubMobi\Request\StatusViberRequest;
use SMSClubMobi\Request\Viber2WayRequest;
use SMSClubMobi\Request\ViberRequest;
use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use SMSClubMobi\Request\ViberSMSRequest;

class ClientTest extends TestCase
{
    private const API_KEY = 'api_key';
    private const TEST_PHONES = ['380971234567', '380971234568'];
    private const TEST_SMS_SENDER = 'SMSSender';
    private const TEST_SMS_MESSAGE = 'SMSMessage';
    private const TEST_VIBER_SENDER = 'ViberSender';
    private const TEST_VIBER_MESSAGE = 'ViberMessage';

    private array $container = [];
    private MockHandler $mock;
    private Client $client;


    protected function setUp(): void
    {
        parent::setUp();
        $this->container = [];
        $this->mock = new MockHandler();
        $history = Middleware::history($this->container);
        $handlerStack = HandlerStack::create($this->mock);
        $handlerStack->push($history);

        $guzzle = new HTTPClient(
            [
            'handler' => $handlerStack,
            'base_uri' => Client::API_URL,
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::API_KEY,
            ],
            ]
        );

        $this->client = new Client(self::API_KEY);
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->client, $guzzle);
    }

    public function testSuccessfulBalance(): void
    {
        $responseData = [
            'success_request' => [
                'info' => [
                    'money' => '8111.1700',
                    'currency' => 'UAH'
                ]
            ]
        ];

        $expectedData = [
            'success' => true,
            'data' => [
                'money' => '8111.1700',
                'currency' => 'UAH'
            ],
            'errors' => [],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = new BalanceRequest();
        $response = $this->client->execute($request);
        $requestInfo = $this->getLastRequest();
        $this->assertRequestMethod($requestInfo);
        $this->assertRequestPath('/sms/balance', $requestInfo);
        $this->assertRequestAuth($requestInfo);
        $this->assertResponseData($expectedData, $response);
    }

    public function testSuccessfulSMSSending(): void
    {
        $responseData = [
            'success_request' => [
                'info' => [
                    '106' => self::TEST_PHONES[0],
                    '107' => self::TEST_PHONES[1],
                ]
            ]
        ];

        $expectedData = [
            'success' => true,
            'data' => [
                '106' => self::TEST_PHONES[0],
                '107' => self::TEST_PHONES[1],
            ],
            'errors' => [],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new SMSRequest())
            ->setPhone(self::TEST_PHONES[0])
            ->setPhone(self::TEST_PHONES[1])
            ->setSender(self::TEST_SMS_SENDER)
            ->setMessage(self::TEST_SMS_MESSAGE)
            ->setLifetime(123)
            ->setIntegrationId(122222);

        $response = $this->client->execute($request);
        $requestInfo = $this->getLastRequest();
        $requestBody = $this->getRequestBody($requestInfo);
        $this->assertRequestMethod($requestInfo);
        $this->assertRequestPath('/sms/send', $requestInfo);
        $this->assertRequestAuth($requestInfo);
        $this->assertRequestPhones($requestBody);
        $this->assertEquals(self::TEST_SMS_SENDER, $requestBody['src_addr']);
        $this->assertEquals(self::TEST_SMS_MESSAGE, $requestBody['message']);
        $this->assertEquals(123, $requestBody['lifetime']);
        $this->assertEquals(122222, $requestBody['integration_id']);
        $this->assertResponseData($expectedData, $response);
    }

    public function testSuccessfulStatusSMS(): void
    {
        $responseData = [
            'success_request' => [
                'info' => [
                    '1234' => 'ENROUTE',
                    '1235' => 'ENROUTE',
                ]
            ]
        ];

        $expectedData = [
            'success' => true,
            'data' => [
                '1234' => 'ENROUTE',
                '1235' => 'ENROUTE',
            ],
            'errors' => [],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new StatusSMSRequest())
            ->setSMSId(1234)
            ->setSMSId(1235);

        $response = $this->client->execute($request);
        $requestInfo = $this->getLastRequest();
        $requestBody = $this->getRequestBody($requestInfo);
        $this->assertRequestMethod($requestInfo);
        $this->assertRequestPath('/sms/status', $requestInfo);
        $this->assertRequestAuth($requestInfo);

        $this->assertCount(2, $requestBody['id_sms']);
        $this->assertEquals([1234, 1235], $requestBody['id_sms']);
        $this->assertResponseData($expectedData, $response);
    }

    public function testSuccessfulViberSending(): void
    {
        $responseData = [
            'success_request' => [
                'info' => [
                    '106' => self::TEST_PHONES[0],
                    '107' => self::TEST_PHONES[1],
                ]
            ]
        ];

        $expectedData = [
            'success' => true,
            'data' => [
                '106' => self::TEST_PHONES[0],
                '107' => self::TEST_PHONES[1],
            ],
            'errors' => [],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new ViberRequest())
            ->setPhone(self::TEST_PHONES[0])
            ->setPhone(self::TEST_PHONES[1])
            ->setSender(self::TEST_VIBER_SENDER)
            ->setMessage(self::TEST_VIBER_MESSAGE)
            ->setLink('https://smsclub.mobi/')
            ->setImage('https://smsclub.mobi/img/og_logo.jpg')
            ->setButtonText('Test');

        $response = $this->client->execute($request);
        $requestInfo = $this->getLastRequest();
        $requestBody = $this->getRequestBody($requestInfo);
        $this->assertRequestMethod($requestInfo);
        $this->assertRequestPath('/vibers/send', $requestInfo);
        $this->assertRequestAuth($requestInfo);
        $this->assertRequestViberPhones($requestBody);
        $this->assertEquals(self::TEST_VIBER_SENDER, $requestBody['sender']);
        $this->assertEquals(self::TEST_VIBER_MESSAGE, $requestBody['message']);
        $this->assertEquals('Test', $requestBody['button_txt']);
        $this->assertEquals('https://smsclub.mobi/', $requestBody['button_url']);
        $this->assertEquals('https://smsclub.mobi/img/og_logo.jpg', $requestBody['picture_url']);
        $this->assertResponseData($expectedData, $response);
    }

    public function testSuccessfulViber2WaySending(): void
    {
        $responseData = [
            'success_request' => [
                'info' => [
                    '106' => self::TEST_PHONES[0],
                    '107' => self::TEST_PHONES[1],
                ]
            ]
        ];

        $expectedData = [
            'success' => true,
            'data' => [
                '106' => self::TEST_PHONES[0],
                '107' => self::TEST_PHONES[1],
            ],
            'errors' => [],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new Viber2WayRequest())
            ->setPhone(self::TEST_PHONES[0])
            ->setPhone(self::TEST_PHONES[1])
            ->setSender(self::TEST_VIBER_SENDER)
            ->setMessage(self::TEST_VIBER_MESSAGE)
            ->setMessage(self::TEST_VIBER_MESSAGE);

        $response = $this->client->execute($request);
        $requestInfo = $this->getLastRequest();
        $requestBody = $this->getRequestBody($requestInfo);
        $this->assertRequestMethod($requestInfo);
        $this->assertRequestPath('/vibers/chat', $requestInfo);
        $this->assertRequestAuth($requestInfo);
        $this->assertRequestViberPhones($requestBody);
        $this->assertEquals(self::TEST_VIBER_SENDER, $requestBody['sender']);
        $this->assertEquals(self::TEST_VIBER_MESSAGE, $requestBody['message']);
        $this->assertTrue($requestBody['is_2way']);
        $this->assertResponseData($expectedData, $response);
    }

    public function testSuccessfulViberSMSSending(): void
    {
        $responseData = [
            'success_request' => [
                'info' => [
                    '106' => self::TEST_PHONES[0],
                    '107' => self::TEST_PHONES[1],
                ]
            ]
        ];

        $expectedData = [
            'success' => true,
            'data' => [
                '106' => self::TEST_PHONES[0],
                '107' => self::TEST_PHONES[1],
            ],
            'errors' => [],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new ViberSMSRequest())
            ->setPhone(self::TEST_PHONES[0])
            ->setPhone(self::TEST_PHONES[1])
            ->setSender(self::TEST_VIBER_SENDER)
            ->setMessage(self::TEST_VIBER_MESSAGE)
            ->setMessage(self::TEST_VIBER_MESSAGE)
            ->setSMSSender(self::TEST_SMS_SENDER)
            ->setSMSMessage(self::TEST_SMS_MESSAGE)
            ->setLink('https://smsclub.mobi/')
            ->setImage('https://smsclub.mobi/img/og_logo.jpg')
            ->setButtonText('Test');

        $response = $this->client->execute($request);
        $requestInfo = $this->getLastRequest();
        $requestBody = $this->getRequestBody($requestInfo);
        $this->assertRequestMethod($requestInfo);
        $this->assertRequestPath('/vibers/send', $requestInfo);
        $this->assertRequestAuth($requestInfo);
        $this->assertRequestViberPhones($requestBody);
        $this->assertEquals(self::TEST_VIBER_SENDER, $requestBody['sender']);
        $this->assertEquals(self::TEST_VIBER_MESSAGE, $requestBody['message']);
        $this->assertEquals(self::TEST_SMS_SENDER, $requestBody['senderSms']);
        $this->assertEquals(self::TEST_SMS_MESSAGE, $requestBody['messageSms']);
        $this->assertEquals('Test', $requestBody['button_txt']);
        $this->assertEquals('https://smsclub.mobi/', $requestBody['button_url']);
        $this->assertEquals('https://smsclub.mobi/img/og_logo.jpg', $requestBody['picture_url']);
        $this->assertResponseData($expectedData, $response);
    }

    public function testSuccessfulStatusViber(): void
    {
        $responseData = [
            'success_request' => [
                'info' => [
                    '1234' => 'ENROUTE',
                    '1235' => 'ENROUTE',
                ]
            ]
        ];

        $expectedData = [
            'success' => true,
            'data' => [
                '1234' => 'ENROUTE',
                '1235' => 'ENROUTE',
            ],
            'errors' => [],
            'error' => '',
        ];

        $this->mock->append(new Response(200, [], json_encode($responseData)));

        $request = (new StatusViberRequest())
            ->setMessageId(1234)
            ->setMessageId(1235);

        $response = $this->client->execute($request);
        $requestInfo = $this->getLastRequest();
        $requestBody = $this->getRequestBody($requestInfo);
        $this->assertRequestMethod($requestInfo);
        $this->assertRequestPath('/vibers/send', $requestInfo);
        $this->assertRequestAuth($requestInfo);

        $this->assertCount(2, $requestBody['messageIds']);
        $this->assertEquals([1234, 1235], $requestBody['messageIds']);
        $this->assertResponseData($expectedData, $response);
    }

    private function getLastRequest(): object
    {
        return end($this->container)['request'];
    }

    private function getRequestBody(object $request): array
    {
        return json_decode($request->getBody()->getContents(), true);
    }

    private function assertRequestMethod(object $request): void
    {
        $this->assertEquals('POST', $request->getMethod());
    }

    private function assertRequestPath(string $path, object $request): void
    {
        $this->assertEquals($path, $request->getUri()->getPath());
    }

    private function assertRequestAuth(object $request): void
    {
        $expectedHeader = 'Bearer ' . self::API_KEY;
        $authorizationHeader = $request->getHeaderLine('Authorization');
        $this->assertEquals($expectedHeader, $authorizationHeader);
    }

    private function assertRequestPhones(array $requestBody): void
    {
        $this->assertCount(2, $requestBody['phone']);
        $this->assertEquals(self::TEST_PHONES, $requestBody['phone']);
    }

    private function assertRequestViberPhones(array $requestBody): void
    {
        $this->assertCount(2, $requestBody['phones']);
        $this->assertEquals(self::TEST_PHONES, $requestBody['phones']);
    }

    private function assertResponseData(array $expectedData, object $response): void
    {
        $this->assertEquals($expectedData, $response->toArray());
    }


}