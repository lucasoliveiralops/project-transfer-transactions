<?php

namespace HyperfTest\Cases\API;

use App\Enum\UserType;
use App\Model\User;
use App\Model\UserIdentifier;
use App\Model\Wallet;
use App\Service\Transaction\Authorizer\AuthorizerProviderInterface;
use App\Service\Transaction\Authorizer\TransactionAuthorizerService;
use Faker\Generator;
use Hyperf\Context\ApplicationContext;
use Hyperf\Event\EventDispatcher;
use Hyperf\Stringable\Str;
use HyperfTest\Cases\Factory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Mockery;
use Swoole\Http\Status;
use Faker\Factory as Faker;

class TransferTransactionTest extends HttpTestCase
{
    use Factory;

    private User $payer;
    private User $payee;
    private Generator $faker;


    private const ENDPOINT = '/transactions/transfer';

    public function setUp(): void
    {
        parent::setUp();

        $this->payer = $this->factory(User::class);
        $this->factory(UserIdentifier::class, ['user_id' => $this->payer->id]);
        $this->factory(Wallet::class, ['user_id' => $this->payer->id]);

        $this->payee = $this->factory(User::class, [
            'type' => UserType::Seller->value
        ]);
        $this->factory(UserIdentifier::class, ['user_id' => $this->payee->id]);
        $this->factory(Wallet::class, ['user_id' => $this->payee->id]);

        $this->mockEventDispatcher();

        $this->faker = Faker::create();
    }

    private function mockEventDispatcher(): void
    {
        $mock = Mockery::mock(EventDispatcherInterface::class)
            ->shouldReceive('dispatch')
            ->andReturn()
            ->getMock()
            ->makePartial();

        ApplicationContext::getContainer()->define(
            EventDispatcher::class,
            $mock
        );
    }
    private function mockTransactionAuthorizedService(bool $return = true): void
    {
        $mock = Mockery::mock(AuthorizerProviderInterface::class)
            ->shouldReceive('authorize')
            ->andReturn($return);

        ApplicationContext::getContainer()->define(
            TransactionAuthorizerService::class,
            fn() => $mock->getMock()->makePartial(),
        );
    }

    private static function getPayload(string $payerId, string $payeeId, mixed $amount): array
    {
        return [
            'payer' => $payerId,
            'payee' => $payeeId,
            'value' => $amount
        ];
    }

    public static function providerWithInvalidData(): array
    {
        return [
            [
                self::getPayload(Str::random(10), Str::uuid()->toString(), 10),
            ],
            [
                self::getPayload(Str::uuid()->toString(), Str::random(10), 10),
            ],
            [
                self::getPayload(Str::uuid()->toString(), Str::uuid()->toString(), Str::random(10)),
            ],
            [
                [],
            ],
        ];
    }

    /**
     * @dataProvider providerWithInvalidData
     */
    public function test_validation_rules_return_error(array $payload): void
    {
        $this->mockTransactionAuthorizedService();

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $payload,
        ]);
        $this->assertEquals(Status::UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    private static function randomNumberFloat(int $minDecimalNumber, int $max = 2): float
    {
        $number = rand(1, $max) . '.' . rand($minDecimalNumber, 10000000);

        return (float) $number;
    }

    public static function providerWithInvalidValues(): array
    {
        return [
            [self::randomNumberFloat(150)],
            [self::randomNumberFloat(1500000)],
            [-self::randomNumberFloat(150000)],
            [-rand(1, 10000000000)],
            [0]
        ];
    }

    /**
     * @dataProvider providerWithInvalidValues
     */
    public function test_validation_value_is_invalid_float_return_error(mixed $value): void
    {
        $this->mockTransactionAuthorizedService();

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $this->getPayload($this->payer->id, $this->payee->id, $value),
        ]);
        $this->assertEquals(Status::UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }

    public function test_payer_not_found_return_404(): void
    {
        $this->mockTransactionAuthorizedService();

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $this->getPayload(Str::uuid()->toString(), $this->payee->id, 10.00),
        ]);

        $this->assertEquals(Status::NOT_FOUND, $response->getStatusCode());
    }

    public function test_payee_not_found_return_404(): void
    {
        $this->mockTransactionAuthorizedService();

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $this->getPayload($this->payer->id, Str::uuid()->toString(), 10.00),
        ]);

        $this->assertEquals(Status::NOT_FOUND, $response->getStatusCode());
    }

    public function test_payer_is_seller_return_403(): void
    {
        $this->mockTransactionAuthorizedService();

        $maxValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: 5000);
        $randValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: $maxValue);

        $payer = $this->factory(User::class, ['type' => UserType::Seller->value]);
        $this->factory(UserIdentifier::class, ['user_id' => $payer->id]);
        $this->factory(Wallet::class, ['user_id' => $payer->id, 'current_balance' => $maxValue]);

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $this->getPayload($payer->id, $this->payee->id, $randValue),
        ]);

        $this->assertEquals(Status::FORBIDDEN, $response->getStatusCode());
    }

    public function test_payer_transfer_to_seller_without_money_return_403(): void
    {
        $this->mockTransactionAuthorizedService();

        $maxValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: 5000);
        $randValue = $this->faker->randomFloat(nbMaxDecimals: 2, min: $maxValue + 1);

        $payer = $this->factory(User::class);
        $this->factory(UserIdentifier::class, ['user_id' => $payer->id]);
        $this->factory(Wallet::class, ['user_id' => $payer->id, 'current_balance' => $maxValue]);

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $this->getPayload($payer->id, $this->payee->id, $randValue),
        ]);

        $this->assertEquals(Status::FORBIDDEN, $response->getStatusCode());
    }

    public function test_user_transfer_to_saller_return_201(): void
    {
        $this->mockTransactionAuthorizedService();

        $maxValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: 5000);
        $randValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: $maxValue);

        $this->payer->wallet->update([
                'current_balance' => $maxValue]
        );

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $this->getPayload($this->payer->id, $this->payee->id, $randValue),
        ]);

        $this->assertEquals(Status::CREATED, $response->getStatusCode());
    }

    public function test_user_transfer_to_user_return_201(): void
    {
        $this->mockTransactionAuthorizedService();

        $maxValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: 5000);
        $randValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: $maxValue);

        $this->payer->wallet->update([
                'current_balance' => $maxValue]
        );

        $this->payee->update([
            'type' => UserType::Seller->value
        ]);

        $response = $this->request('POST', self::ENDPOINT, [
            'headers' => [],
            'form_params' => $this->getPayload($this->payer->id, $this->payee->id, $randValue),
        ]);

        $this->assertEquals(Status::CREATED, $response->getStatusCode());
    }
}