<?php

namespace App\Services;

interface SMSClientInterface
{
    public function send(string $phoneNumber, string $message): array;
}
