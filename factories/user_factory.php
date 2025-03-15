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
use App\Domain\Enum\UserType;
use App\Infraestructure\Model\User;
use Faker\Factory as Faker;
use Hyperf\Database\Model\Factory;

$faker = Faker::create('pt_BR');

/* @var Factory $factory */
$factory->define(User::class, function () use ($faker) {
    return [
        'name' => $faker->name,
        'password' => md5($faker->password),
        'email' => $faker->email,
        'type' => UserType::DefaultUser->value,
    ];
});
