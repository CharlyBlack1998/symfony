<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.name LIKE :name')
            ->setParameter('name', "%{$name}%")
            ->orderBy('u.name', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}