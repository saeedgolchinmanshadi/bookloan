<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;
use App\Entity\BookLoan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BookLoan>
 */
class BookLoanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BookLoan::class);
    }

    public function findActiveLoans(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.returnedAt IS NULL')
            ->andWhere('b.type = :type')
            ->setParameter('type', 'loan')
            ->orderBy('b.borrowDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findDueInNextWeek(): array
    {
        $nextWeek = new \DateTimeImmutable('+7 days');
        $now = new \DateTimeImmutable('now');

        return $this->createQueryBuilder('b')
            ->andWhere('b.returnedAt IS NULL')
            ->andWhere('b.type = :type')
            ->andWhere('b.dueDate BETWEEN :now AND :nextWeek')
            ->setParameter('type', 'loan')
            ->setParameter('now', $now)
            ->setParameter('nextWeek', $nextWeek)
            ->orderBy('b.dueDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findMemberHistory(int $memberId): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.member = :memberId')
            ->setParameter('memberId', $memberId)
            ->orderBy('b.borrowDate', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function searchAndFilter(?string $searchQuery, ?string $status, ?int $subjectId = null): QueryBuilder
    {
        $qb = $this->createQueryBuilder('bl')
            ->leftJoin('bl.book', 'b')
            ->leftJoin('bl.member', 'm')
            ->addSelect('b', 'm')
            ->orderBy('bl.borrowDate', 'DESC');

        if ($searchQuery) {
            $qb->andWhere('b.title LIKE :search OR m.lastName LIKE :search OR m.nationalCode LIKE :search')
                ->setParameter('search', '%' . $searchQuery . '%');
        }

        if ($status === 'returned') {
            $qb->andWhere('bl.returnedAt IS NOT NULL');
        } elseif ($status === 'active') {
            $qb->andWhere('bl.returnedAt IS NULL');
        }

        if ($subjectId) {
            $qb->leftJoin('b.subjects', 's')
                ->andWhere('s.id = :subjectId')
                ->setParameter('subjectId', $subjectId);
        }

        return $qb;
    }

    public function hasActiveLoanForBook(\App\Entity\Book $book, ?int $excludeLoanId = null): bool
    {
        $qb = $this->createQueryBuilder('bl')
            ->select('COUNT(bl.id)')
            ->andWhere('bl.book = :book')
            ->andWhere('bl.returnedAt IS NULL')
            ->andWhere('bl.type = :type')
            ->setParameter('book', $book)
            ->setParameter('type', 'loan');

        if ($excludeLoanId !== null) {
            $qb->andWhere('bl.id != :excludeId')
                ->setParameter('excludeId', $excludeLoanId);
        }

        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }

    public function hasActiveReservationForMemberAndBook(
        \App\Entity\Member $member,
        \App\Entity\Book $book,
        ?int $excludeLoanId = null,
    ): bool {
        $qb = $this->createQueryBuilder('bl')
            ->select('COUNT(bl.id)')
            ->andWhere('bl.book = :book')
            ->andWhere('bl.member = :member')
            ->andWhere('bl.returnedAt IS NULL')
            ->andWhere('bl.type = :type')
            ->setParameter('book', $book)
            ->setParameter('member', $member)
            ->setParameter('type', 'reservation');

        if ($excludeLoanId !== null) {
            $qb->andWhere('bl.id != :excludeId')
                ->setParameter('excludeId', $excludeLoanId);
        }

        return (int) $qb->getQuery()->getSingleScalarResult() > 0;
    }

    public function countOverdue(): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->where('l.returnedAt IS NULL')
            ->andWhere('l.dueDate < :now')
            ->setParameter('now', new \DateTimeImmutable())
            ->getQuery()
            ->getSingleScalarResult();
    }
}
