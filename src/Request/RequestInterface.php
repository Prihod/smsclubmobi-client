<?php

namespace SMSClubMobi\Request;

interface RequestInterface
{
    public function getUri(): string;

    public function toArray(): array;
}
