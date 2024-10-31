<?php

namespace SMSClubMobi;

use SMSClubMobi\Exception\RequestException;
use SMSClubMobi\Request\RequestInterface;
use SMSClubMobi\Response\Response;
use SMSClubMobi\Response\ResponseInterface;
use GuzzleHttp\Client as HTTPClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Client to use the smsclub.mobi API
 *
 * @link https://smsclub.mobi/api/
 */
class Client
{
    const API_URL = 'https://im.smsclub.mobi/';
    protected ClientInterface $client;

    public function __construct(string $apiKey)
    {
        $this->client = new HTTPClient(
            [
                'base_uri' => self::API_URL,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $apiKey,
                ],
            ]
        );
    }


    /**
     * @param ResponseInterface[]|RequestInterface $request
     * @param array                                $options
     *
     * @return ResponseInterface
     * @throws RequestException
     */
    public function execute($request, array $options = []): ResponseInterface
    {
        try {
            $options = array_merge(
                ['json' => $request->toArray()],
                $options
            );
            $response = $this->client->post($request->getUri(), $options);
            return Response::fromJson($response->getBody()->getContents());
        } catch (GuzzleException $e) {
            throw new RequestException(
                $request,
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}