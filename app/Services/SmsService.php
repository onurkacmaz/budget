<?php

namespace App\Services;

class SmsService
{
    public function __construct(protected SMSClientInterface $client){}

    public function send(string $phoneNumber, string $message): array {
        return $this->client->send($phoneNumber, $message);
    }

    public function generateCode(): string {
        return rand(100000, 999999);
    }
}
