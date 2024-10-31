<?php

namespace SMSClubMobi\Request;

class Viber2WayRequest extends Request
{

    protected string $uri = 'vibers/chat';
    protected array $phones = [];
    protected string $sender;
    protected string $message;

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


    public function toArray(): array
    {
        return [
            'phones' => $this->phones,
            'sender' => $this->sender,
            'message' => $this->message,
            'is_2way' => true,
        ];

    }

}