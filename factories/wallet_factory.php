<?php

use Hyperf\Database\Model\Factory;
use Faker\Factory as Faker;
use App\Model\Wallet;
use App\Model\User;

use function Hyperf\Support\env;

$faker = Faker::create('pt_BR');

/* @var Factory $factory */
$factory->define(Wallet::class, function () use ($faker) {
    return  [
        'user_id' => fn() => Factory::construct($faker, env('FACTORY_PATH'))
            ->of(User::class)->create()->id,
        'current_balance' => $faker->randomFloat(2),
    ];
});
