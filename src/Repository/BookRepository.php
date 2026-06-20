<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function createSearchQueryBuilder(?string $searchQuery): \Doctrine\ORM\QueryBuilder
    {
        $qb = $this->createQueryBuilder('b')
            ->orderBy('b.id', 'DESC');

        $searchQuery = trim((string) $searchQuery);
        if ($searchQuery !== '') {
            $qb->andWhere('b.title LIKE :search OR b.author LIKE :search')
                ->setParameter('search', '%' . $searchQuery . '%');
        }

        return $qb;
    }
}
