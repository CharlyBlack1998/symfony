<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findByTitle(string $title): array
    {
         return $this->createQueryBuilder('pr')
            ->where('pr.title LIKE :title')
            ->setParameter('title', "%{$title}%")
            ->getQuery()
            ->getResult()
        ;
    }
}
