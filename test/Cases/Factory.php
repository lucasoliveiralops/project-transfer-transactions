<?php

namespace HyperfTest\Cases;

use App\Model\Model;
use Faker\Factory as Faker;
use Hyperf\Database\Model\Factory as FactoryDatabase;

use function Hyperf\Support\env;

trait Factory
{
    private function factory(string $class, array $data = [])
    {
        $faker = Faker::create('pt_BR');
        $factory = FactoryDatabase::construct($faker, env('FACTORY_PATH'));

        return $factory->of($class)->create($data);
    }
}