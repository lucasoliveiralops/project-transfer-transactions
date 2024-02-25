<?php

namespace HyperfTest\Cases\Services;

use App\Model\User;
use App\Model\UserIdentifier;
use App\Model\Wallet;
use App\Service\Wallet\WalletService;
use Hyperf\Stringable\Str;
use HyperfTest\Cases\Factory;
use Hyperf\Testing\TestCase;

use function Hyperf\Support\make;

class WalletServiceTest extends TestCase
{
    use Factory;

    private User $user;
    private WalletService $walletService;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->factory(User::class);
        $this->factory(UserIdentifier::class, ['user_id' => $this->user->id]);
        $this->factory(Wallet::class, ['user_id' => $this->user->id]);

        $this->walletService = make(WalletService::class);
    }

    public function test_verify_user_dont_has_amount_return_false(): void
    {

        $this->user->wallet->update([
            'current_balance' => 9
        ]);
        
        $user = $this->user->find($this->user->id);

        $hasAmount = $this->walletService->hasAmount($user, 10);

        $this->assertFalse($hasAmount);
    }

    public function test_verify_user_has_amount_return_true(): void
    {

        $this->user->wallet->update([
            'current_balance' => 10
        ]);

        $user = $this->user->find($this->user->id);

        $hasAmount = $this->walletService->hasAmount($user, 10);

        $this->assertTrue($hasAmount);
    }
}
