<?php

namespace SMSClubMobi\Request;

class StatusSMSRequest extends Request
{
    protected string $uri = 'sms/status';
    protected array $ids = [];

    public function setSMSId(int $id): self
    {
        $this->ids[] = $id;
        return $this;
    }


    public function toArray(): array
    {
        return [
            'id_sms' => $this->ids
        ];
    }
}

