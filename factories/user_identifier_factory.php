<?php

use Hyperf\Database\Model\Factory;
use Faker\Factory as Faker;
use App\Model\UserIdentifier;
use App\Enum\IdentifiersType;
use App\Model\User;

use function Hyperf\Support\env;

$faker = Faker::create('pt_BR');

/* @var Factory $factory */
$factory->define(UserIdentifier::class, function () use ($faker) {
    $cpf = $faker->cpf;

    return  [
        'user_id' => fn() => Factory::construct($faker, env('FACTORY_PATH'))
            ->of(User::class)->create()->id,
        'identifier' => str_replace(['.', '-'], '', $cpf),
        'type' => IdentifiersType::CPF->value,
    ];
});
