<?php

namespace SMSClubMobi\Request;

class ViberSMSRequest extends ViberRequest
{
    protected string $smsMessage;
    protected string $smsSender;

    public function setSMSSender(string $sender): self
    {
        $this->smsSender = $sender;
        return $this;
    }

    public function setSMSMessage(string $message): self
    {
        $this->smsMessage = $message;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                'senderSms' => $this->smsSender,
                'messageSms' => $this->smsMessage,
            ]
        );

    }

}

