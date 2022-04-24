<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PupupupupUserRepository extends EntityRepository
{


    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('pupupupup')
            ->where('pupupupup.name LIKE :name')
            ->setParameter('name', "%{$name}%")
            ->orderBy('pupupupup.name', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
