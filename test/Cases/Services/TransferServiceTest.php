<?php

namespace HyperfTest\Cases\Services;

use App\Model\User;
use HyperfTest\Cases\Factory;
use Hyperf\Testing\TestCase;

class TransferServiceTest extends TestCase
{
    use Factory;

    public function test_nonexistent_payer_return_error(){
        var_dump($this->factory(User::class));

    }
}