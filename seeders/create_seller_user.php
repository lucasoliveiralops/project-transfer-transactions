<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
use App\Domain\Enum\IdentifiersType;
use App\Domain\Enum\UserType;
use App\Infraestructure\Model\User;
use App\Infraestructure\Model\UserIdentifier;
use App\Infraestructure\Model\Wallet;
use Faker\Factory;
use Faker\Generator;
use Hyperf\Database\Model\Factory as FactoryDatabase;
use Hyperf\Database\Seeders\Seeder;

use function Hyperf\Support\env;

class CreateSellerUser extends Seeder
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('pt_BR');
    }

    public function run(): void
    {
        $factory = FactoryDatabase::construct($this->faker, env('FACTORY_PATH'));

        $user = $factory->of(User::class)->create([
            'type' => UserType::Seller->value,
        ]);

        $cnpj = $this->faker->cnpj;
        $factory->of(UserIdentifier::class)
            ->create(
                [
                    'user_id' => $user->id,
                    'identifier' => str_replace(['.', '-', '/'], '', $cnpj),
                    'type' => IdentifiersType::CNPJ->value,
                ]
            );

        $factory->of(Wallet::class)->create(
            [
                'user_id' => $user->id,
            ]
        );
    }
}
