<?php

namespace HyperfTest\Cases\Services;

use App\Exception\User\UserNotFound;
use App\Model\User;
use App\Model\UserIdentifier;
use App\Model\Wallet;
use App\Service\User\UserService;
use Hyperf\Stringable\Str;
use HyperfTest\Cases\Factory;
use Hyperf\Testing\TestCase;

use function Hyperf\Support\make;

class UserServiceTest extends TestCase
{
    use Factory;

    private User $user;
    private UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->factory(User::class);
        $this->factory(UserIdentifier::class, ['user_id' => $this->user->id]);
        $this->factory(Wallet::class, ['user_id' => $this->user->id]);

        $this->userService = make(UserService::class);
    }

    public function test_nonexistent_user_return_error(): void
    {
        $this->expectException(UserNotFound::class);

        $this->userService->findOrFail(Str::uuid()->toString());
    }

    public function test_existent_user_return_correct_user(): void
    {
        $user = $this->userService->findOrFail($this->user->id);

        $this->assertEquals($this->user->id, $user->id);
        $this->assertEquals($this->user->name, $user->name);
        $this->assertEquals($this->user->wallet->id, $user->wallet->id);
    }
}
