<?php

namespace SMSClubMobi\Response;

interface ResponseInterface
{
    public static function fromJson(string $json): self;

    public function isSuccess(): bool;

    public function getData(): array;

    public function getErrors(): array;

    public function getError(): string;

    public function hasErrors(): bool;

    public function toArray(): array;
}