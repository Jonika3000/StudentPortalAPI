<?php

namespace App\Shared\Response;

class ResponseError
{
    private string $code;
    private string $message;
    private ?array $details = null;

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function setDetails(?array $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDetails(): ?array
    {
        return $this->details;
    }

    public function toArray(): array
    {
        $response = [
            'code' => $this->code,
            'message' => $this->message,
        ];

        if (!empty($this->details)) {
            $response['details'] = $this->details;
        }

        return $response;
    }

    public function serializeToJsonString(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
