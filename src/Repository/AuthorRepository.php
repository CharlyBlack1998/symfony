<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class AuthorRepository extends EntityRepository
{
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.name LIKE :name')
            ->setParameter('name', "%{$name}%")
            ->getQuery()
            ->getResult()
        ;
    }
}
