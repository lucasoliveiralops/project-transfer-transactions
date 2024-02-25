<?php

namespace App\Service\Notification;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Swoole\Http\Status;

use function Hyperf\Support\env;

class NotificationService implements NotificationProviderInterface
{
    private const ENDPOINT = '54dc2cf1-3add-45b5-b5a9-6bf7e7f1f4a6';

    private function getClient(): Client
    {
        return new Client([
            'base_uri' => env('URL_NOTIFICATION_SERVICE'),
            'timeout' => 5,
            'swoole' => [
                'timeout' => 10,
                'socket_buffer_size' => 1024 * 1024 * 2,
            ],
        ]);
    }

    public function send(): bool
    {
        try {
            $response = $this->getClient()->get(self::ENDPOINT);
            $responseBody = json_decode($response->getBody()->getContents());
            $message = !empty($responseBody->message) && $responseBody->message;

            if($response->getStatusCode() == Status::OK && $message) {
                return true;
            }
        } catch (ClientException|ServerException $e) {

            return false;
        }

        return false;
    }
}
