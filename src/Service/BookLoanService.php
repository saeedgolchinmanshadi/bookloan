<?php

namespace App\Service;

use App\Entity\BookLoan;
use Doctrine\ORM\EntityManagerInterface;

final class BookLoanService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function create(BookLoan $bookLoan): void
    {
        $this->entityManager->persist($bookLoan);
        $this->entityManager->flush();
    }

    public function update(BookLoan $bookLoan): void
    {
        $this->entityManager->flush();
    }

    public function delete(BookLoan $bookLoan): void
    {
        $this->entityManager->remove($bookLoan);
        $this->entityManager->flush();
    }

    public function markAsReturned(BookLoan $bookLoan): void
    {
        if ($bookLoan->getReturnedAt() !== null) {
            throw new \LogicException('این امانت قبلاً بازگردانده شده است.');
        }

        $bookLoan->setReturnedAt(new \DateTimeImmutable());
        $this->entityManager->flush();
    }
}
