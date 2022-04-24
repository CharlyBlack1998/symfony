<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ShopRepository extends EntityRepository
{
    public function findByTitle(string $title): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.title LIKE :title')
            ->setParameter('title', "%{$title}%")
            ->getQuery()
            ->getResult()
        ;
    }
}
