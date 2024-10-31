<?php

namespace SMSClubMobi\Request;

abstract class Request implements RequestInterface
{
    protected string $uri;

    public static function preparePhone(string $phone): int
    {
        return (int)preg_replace('/\D/', '', $phone);
    }

    public function getUri(): string
    {
        return $this->uri;
    }


    public function toArray(): array
    {
        return [];
    }

    public function __toString(): string
    {
        return (string)json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}
