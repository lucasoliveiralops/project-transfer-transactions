<?php

use Hyperf\Database\Model\Factory;
use Faker\Factory as Faker;
use App\Model\User;
use App\Enum\UserType;

$faker = Faker::create('pt_BR');

/* @var Factory $factory */
$factory->define(User::class, function () use ($faker) {
   return  [
       'name' => $faker->name,
       'password' => md5($faker->password),
       'email' => $faker->email,
       'type' => UserType::DefaultUser->value
   ];
});
