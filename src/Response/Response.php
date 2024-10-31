<?php

namespace SMSClubMobi\Response;

use SMSClubMobi\Response\Entry\Entry;
use SMSClubMobi\Response\Entry\EntryInterface;

class Response implements ResponseInterface
{
    const E_INVALID_JSON = 'Invalid json';
    const E_UNKNOWN_ERROR = 'Unknown error';
    const E_BAD_RESPONSE = 'Bad response';

    protected bool $success;
    protected array $data = [];
    protected array $errors = [];
    protected string $error = '';

    public static function fromJson(string $json): self
    {
        if (!$json) {
            return new Response(false, [], [], self::E_BAD_RESPONSE);
        }

        $responseData = json_decode($json, true);
        if (!$responseData) {
            return new Response(false, [], [], self::E_INVALID_JSON);
        }

        if (isset($responseData['success_request'])) {
            $result = $responseData['success_request'];
            return new Response(true, $result['info'] ?? [], $result['add_info'] ?? []);
        }

        return new Response(false, [], [], self::E_UNKNOWN_ERROR);
    }


    public function __construct(bool $success, array $data, array $errors = [], string $error = '')
    {
        $this->success = $success;
        $this->data = $data;
        $this->errors = $errors;
        $this->error = $error;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function toArray(): array
    {
        return [
            'success' => $this->isSuccess(),
            'data' => $this->getData(),
            'errors' => $this->getErrors(),
            'error' => $this->getError(),
        ];
    }

    public function __toString()
    {
        return (string)json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

}