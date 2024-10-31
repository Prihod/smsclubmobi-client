<?php

namespace SMSClubMobi\Request;

class ViberRequest extends Request
{

    protected string $uri = 'vibers/send';
    protected array $phones = [];
    protected string $sender;
    protected string $message;
    protected string $image = '';
    protected string $link;
    protected string $buttonText = '';

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

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;
        return $this;
    }

    public function setButtonText(string $text): self
    {
        $this->buttonText = $text;
        return $this;
    }


    public function toArray(): array
    {
        return [
            'phones' => $this->phones,
            'sender' => $this->sender,
            'message' => $this->message,
            'button_url' => $this->link,
            'button_txt' => $this->buttonText,
            'picture_url' => $this->image,
        ];

    }

}