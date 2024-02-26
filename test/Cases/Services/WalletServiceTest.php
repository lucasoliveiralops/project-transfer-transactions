<?php

namespace HyperfTest\Cases\Services;

use App\Exception\Transaction\InsufficientBalanceForTransaction;
use App\Model\User;
use App\Model\UserIdentifier;
use App\Model\Wallet;
use App\Service\Wallet\WalletService;
use Faker\Factory as Faker;
use Faker\Generator;
use HyperfTest\Cases\Factory;
use Hyperf\Testing\TestCase;

use function Hyperf\Support\make;

class WalletServiceTest extends TestCase
{
    use Factory;

    private User $user;
    private WalletService $walletService;
    private Generator $faker;


    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->factory(User::class);
        $this->factory(UserIdentifier::class, ['user_id' => $this->user->id]);
        $this->factory(Wallet::class, ['user_id' => $this->user->id]);

        $this->walletService = make(WalletService::class);

        $this->faker = Faker::create();
    }

    public function test_remove_balance_dont_has_amount_throw_error(): void
    {
        $this->expectException(InsufficientBalanceForTransaction::class);

        $this->user->wallet->update([
            'current_balance' => $this->faker->randomFloat(nbMaxDecimals: 2, max: 9)
        ]);

        $this->walletService->removeBalance($this->user->id, 10.1);
    }

    public function test_remove_all_balance_return_success(): void
    {
        $currentBalance = $this->faker->randomFloat(nbMaxDecimals: 2);

        $this->user->wallet->update([
            'current_balance' => $currentBalance
        ]);

        $this->walletService->removeBalance($this->user->id, $currentBalance);
        $this->assertEquals(0, $this->user->wallet()->first()->current_balance);
    }

    public function test_remove_balance_return_success(): void
    {
        $currentBalance = $this->faker->randomFloat(nbMaxDecimals: 2, min: 100);

        $this->user->wallet->update([
            'current_balance' => $currentBalance
        ]);

        $this->walletService->removeBalance($this->user->id, 99);
        $this->assertEquals($currentBalance - 99, $this->user->wallet()->first()->current_balance);

    }

    public function test_add_balance_return_success(): void
    {
        $addBalance = $this->faker->randomFloat(nbMaxDecimals: 2, min: 100);

        $oldBalance = $this->user->wallet->current_balance;

        $this->walletService->addBalance($this->user->id, $addBalance);
        $this->assertEquals($oldBalance + $addBalance, $this->user->wallet()->first()->current_balance);
    }
}
