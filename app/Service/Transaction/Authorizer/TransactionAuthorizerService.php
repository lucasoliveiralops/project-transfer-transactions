<?php

declare(strict_types=1);

namespace App\Service\Transaction\Authorizer;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\CoroutineHandler;

use Swoole\Http\Status;
use function Hyperf\Support\env;

class TransactionAuthorizerService implements AuthorizerProviderInterface
{
    private const ENDPOINT = '5794d450-d2e2-4412-8131-73d0293ac1cc';

    private function getClient(): Client
    {
        return new Client([
            'base_uri' => env('URL_TRANSACTION_AUTHORIZER'),
            'handler' => HandlerStack::create(new CoroutineHandler()),
            'timeout' => 5,
            'swoole' => [
                'timeout' => 10,
                'socket_buffer_size' => 1024 * 1024 * 2,
            ],
        ]);
    }

    public function authorize(): bool
    {
        try {
            $response = $this->getClient()->get(self::ENDPOINT);
            $responseBody = json_decode($response->getBody()->getContents());
            $message = !empty($responseBody->message) ? $responseBody->message : false;

            if($response->getStatusCode() == Status::OK && $message == 'Autorizado') {
                return true;
            }
        } catch (ClientException|ServerException $e) {
            return false;
        }

        return false;
    }
}