<?php

namespace App\Service;

use App\Entity\User;
use Faker\Factory;

class UserGenerator
{
    public function generateUser(): User
    {
        $faker = Factory::create();

        return (new User())
            ->setName($faker->firstName)
            ->setSurname($faker->lastName)
            ->setAge($faker->numberBetween(1, 100))
            ->setEmail($faker->email)
            ->setPassword($faker->password);
    }

    public function generateUsers(): array
    {
        $users = [];
        for ($i = 0; $i < 20; $i++) {
            $users[] = $this->generateUser();
        }

        return $users;
    }
}
