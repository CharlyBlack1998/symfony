<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

final class BookRepository extends EntityRepository
{
    public function findByPagination(int $page, int $limit): array
    {
        return $this
            ->createQueryBuilder('b')
            ->setMaxResults($limit)
            ->setFirstResult($page * $limit - $limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByTitle(string $title): array
    {
        return $this
            ->createQueryBuilder('b')
            ->where('b.title LIKE :title')
            ->setParameter('title', "%{$title}%")
            ->orderBy('b.title', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
