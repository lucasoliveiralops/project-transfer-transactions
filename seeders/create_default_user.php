<?php

declare(strict_types=1);

use Hyperf\Database\Seeders\Seeder;
use App\Model\User;
use Faker\Factory;
use Faker\Generator;
use App\Enum\IdentifiersType;
use App\Enum\UserType;

class CreateDefaultUser extends Seeder
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('pt_BR');
    }

    public function run(): void
    {
        $user = User::create(
            [
                'name' => $this->faker->name,
                'password' => md5($this->faker->password),
                'email' => $this->faker->email,
                'type' => UserType::DefaultUser->value
            ]
        );

        $cpf = $this->faker->cpf;
        $user->identifier()->create(
            [
                'identifier' => str_replace(['.', '-'], '', $cpf),
                'type' => IdentifiersType::CPF->value,
            ]
        );

        $user->wallet()->create(
            [
                'current_balance' => $this->faker->randomFloat(2)
            ]
        );
    }
}
