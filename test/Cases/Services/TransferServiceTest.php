<?php

namespace HyperfTest\Cases\Services;

use App\Enum\UserType;
use App\Exception\Transaction\ForbiddenTransferForSeller;
use App\Exception\Transaction\InsufficientBalanceForTransaction;
use App\Exception\Transaction\UnauthorizedTransaction;
use App\Exception\User\UserNotFound;
use App\Model\User;
use App\Model\UserIdentifier;
use App\Model\Wallet;
use App\Service\Transaction\Authorizer\AuthorizerProviderInterface;
use App\Service\Transaction\TransferService;
use Faker\Generator;
use Mockery;
use Hyperf\Stringable\Str;
use HyperfTest\Cases\Factory;
use Hyperf\Testing\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Faker\Factory as Faker;

use function Hyperf\Support\make;

class TransferServiceTest extends TestCase
{
    use Factory;

    private User $payer;
    private User $payee;
    private TransferService $transferService;
    private Generator $faker;


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

        $this->transferService = make(TransferService::class, [
            'authorizationService' => $this->mockTransactionAuthorizedService(),
            'eventDispatcher' => $this->mockEventDispatcher()
        ]);


        $this->faker = Faker::create();
    }

    private function mockTransactionAuthorizedService(bool $return = true): AuthorizerProviderInterface
    {
        return Mockery::mock(AuthorizerProviderInterface::class)
            ->shouldReceive('authorize')
            ->andReturn($return)
            ->getMock()
            ->makePartial();
    }

    private function mockEventDispatcher(): EventDispatcherInterface
    {
        return Mockery::mock(EventDispatcherInterface::class)
            ->shouldReceive('dispatch')
            ->andReturn()
            ->getMock()
            ->makePartial();
    }

    public function test_nonexistent_payer_return_error(): void
    {
        $this->expectException(UserNotFound::class);

        $this->transferService->transfer(Str::uuid()->toString(), $this->payee->id, 100);
    }

    public function test_nonexistent_payee_return_error(): void
    {
        $this->expectException(UserNotFound::class);

        $this->transferService->transfer($this->payer->id, Str::uuid()->toString(), 100);
    }

    public function test_seller_transfer_to_user_return_error(): void
    {
        $this->expectException(ForbiddenTransferForSeller::class);

        $seller = $this->factory(User::class, [
            'type' => UserType::Seller->value
        ]);

        $this->transferService->transfer($seller->id, $this->payee->id, 100);
    }

    public function test_user_transfer_to_seller_without_money_return_error(): void
    {
        $this->expectException(InsufficientBalanceForTransaction::class);

        $this->payer->wallet->update([
            'current_balance' => 10.00
        ]);

        $this->transferService->transfer($this->payer->id, $this->payee->id, 11);
    }

    public function test_user_transfer_to_seller_but_transaction_return_not_authorized(): void
    {
        $this->expectException(UnauthorizedTransaction::class);

        $this->payer->wallet->update([
            'current_balance' => 50.00
        ]);

        $transferService = make(TransferService::class, [
            'authorizationService' => $this->mockTransactionAuthorizedService(return: false),
            'eventDispatcher' => $this->mockEventDispatcher()
        ]);

        $transferService->transfer($this->payer->id, $this->payee->id, 11);
    }

    public function test_user_transfer_to_seller_return_success(): void
    {
        $this->payer->wallet->update([
            'current_balance' => 50.00
        ]);

        $randValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: 50);

        $payerBalance = $this->payer->wallet()->first()->current_balance;
        $payeeBalance = $this->payee->wallet()->first()->current_balance;

        $this->transferService->transfer($this->payer->id, $this->payee->id, $randValue);

        $actualPayerBalance = $this->payer->wallet()->first()->current_balance;
        $actualPayeeBalance = $this->payee->wallet()->first()->current_balance;


        $this->assertEquals(round($payerBalance - $randValue, 2), $actualPayerBalance);
        $this->assertEquals(round($payeeBalance + $randValue, 2), $actualPayeeBalance);
    }

    public function test_user_transfer_to_user_return_success(): void
    {
        $this->payer->wallet->update([
            'current_balance' => 50.00
        ]);

        $randValue = $this->faker->randomFloat(nbMaxDecimals: 2, max: 50);

        $payee = $this->factory(User::class);
        $this->factory(UserIdentifier::class, ['user_id' => $payee->id]);
        $this->factory(Wallet::class, ['user_id' => $payee->id]);

        $payerBalance = $this->payer->wallet()->first()->current_balance;
        $payeeBalance = $payee->wallet()->first()->current_balance;

        $this->transferService->transfer($this->payer->id, $payee->id, $randValue);

        $actualPayerBalance = $this->payer->wallet()->first()->current_balance;
        $actualPayeeBalance = $payee->wallet()->first()->current_balance;

        $this->assertEquals(round($payerBalance - $randValue, 2), $actualPayerBalance);
        $this->assertEquals(round($payeeBalance + $randValue, 2), $actualPayeeBalance);
    }
}
