<?php

namespace App\Service;

use App\Repository\BookLoanRepository;
use App\Repository\BookRepository;
use App\Repository\MemberRepository;

final class DashboardService
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly MemberRepository $memberRepository,
        private readonly BookLoanRepository $bookLoanRepository,
    ) {
    }

    /**
     * @return array{
     *     total_books: int,
     *     total_members: int,
     *     active_loans: int,
     *     overdue_loans: int,
     *     due_soon_loans: list<\App\Entity\BookLoan>
     * }
     */
    public function getStats(): array
    {
        return [
            'total_books' => $this->bookRepository->count([]),
            'total_members' => $this->memberRepository->count([]),
            'active_loans' => $this->bookLoanRepository->count(['returnedAt' => null]),
            'overdue_loans' => $this->bookLoanRepository->countOverdue(),
            'due_soon_loans' => $this->bookLoanRepository->findDueInNextWeek(),
        ];
    }
}
