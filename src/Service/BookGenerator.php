<?php

namespace App\Service;

use App\Entity\Book;
use Faker\Factory;

class BookGenerator
{

    public function generateBook(): Book
    {
        $faker = Factory::create();

        return (new Book())
            ->setTitle($faker->name)
            ->setAuthor($faker->name)
            ->setPageQuantity($faker->numberBetween(50, 1000))
        ;
    }

    public function generateBooks(): array
    {
        $books = [];
        for ($i = 0; $i < 50; $i++) {
            $books[] = $this->generateBook();
        }

        return $books;
    }
}
