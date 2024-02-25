<?php

declare(strict_types=1);

use Hyperf\Database\Model\Factory as FactoryDatabase;
use Hyperf\Database\Seeders\Seeder;
use App\Model\User;
use App\Model\UserIdentifier;
use App\Model\Wallet;
use Faker\Factory;
use Faker\Generator;

use function Hyperf\Support\env;

class CreateDefaultUser extends Seeder
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('pt_BR');
    }

    public function run(): void
    {
        $factory = FactoryDatabase::construct($this->faker, env('FACTORY_PATH'));

        $user = $factory->of(User::class)->create();

        $factory->of(UserIdentifier::class)
            ->create(
                [
                    'user_id' => $user->id,
                ]
            );

        $factory->of(Wallet::class)->create(
            [
                'user_id' => $user->id,
            ]
        );
    }
}
