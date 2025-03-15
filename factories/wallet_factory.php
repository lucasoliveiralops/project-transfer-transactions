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
use App\Infraestructure\Model\User;
use App\Infraestructure\Model\Wallet;
use Faker\Factory as Faker;
use Hyperf\Database\Model\Factory;

use function Hyperf\Support\env;

$faker = Faker::create('pt_BR');

/* @var Factory $factory */
$factory->define(Wallet::class, function () use ($faker) {
    return [
        'user_id' => fn () => Factory::construct($faker, env('FACTORY_PATH'))
            ->of(User::class)->create()->id,
        'current_balance' => $faker->randomFloat(2),
    ];
});
