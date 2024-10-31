<?php

namespace SMSClubMobi\Request;

class StatusViberRequest extends Request
{
    protected string $uri = 'vibers/send';
    protected array $ids = [];

    public function setMessageId(int $id): self
    {
        $this->ids[] = $id;
        return $this;
    }


    public function toArray(): array
    {
        return [
            'messageIds' => $this->ids
        ];
    }
}

