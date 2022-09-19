<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Exception;
use Illuminate\Support\Facades\Config;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class Twilio implements SMSClientInterface
{
    /**
     * @throws TwilioException
     * @throws ConfigurationException
     * @throws ApiException
     */
    public function send(string $phoneNumber, string $message): array
    {
        $account_sid = Config::get('twilo.account_sid');
        $auth_token = Config::get('twilo.auth_token');

        $twilio_number = Config::get('twilo.twilio_number');

        $client = new Client($account_sid, $auth_token);


        try {
            $response = $client->messages->create(
                $phoneNumber,
                [
                    'from' => $twilio_number,
                    'body' => $message
                ]
            );

            return $response->toArray();
        }catch (Exception) {
            throw new ApiException("SMS_SENDING_FAIL", 422);
        }
    }
}
