<?php

namespace SMSClubMobi\Request;

class SMSRequest extends Request
{
    protected string $uri = 'sms/send';
    protected array $phones = [];
    protected string $sender;
    protected string $message;
    protected ?int $lifetime = null;
    protected ?string $integrationId = null;


    public function setPhone(string $phone): self
    {
        $this->phones[] = self::preparePhone($phone);
        return $this;
    }

    public function setSender(string $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setLifetime(int $lifetime): self
    {
        $this->lifetime = $lifetime;
        return $this;
    }

    public function setIntegrationId(string $id): self
    {
        $this->integrationId = $id;
        return $this;
    }


    public function toArray(): array
    {
        $data = [
            'phone' => $this->phones,
            'src_addr' => $this->sender,
            'message' => $this->message,
        ];
        if ($this->lifetime !== null) {
            $data['lifetime'] = $this->lifetime;
        }
        if ($this->integrationId !== null) {
            $data['integration_id'] = $this->integrationId;
        }
        return $data;
    }


}

