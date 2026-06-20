<?php

namespace App\Validator\Constraints;

use App\Entity\BookLoan;
use App\Repository\BookLoanRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidBookLoanValidator extends ConstraintValidator
{
    public function __construct(
        private readonly BookLoanRepository $bookLoanRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof ValidBookLoan) {
            throw new UnexpectedTypeException($constraint, ValidBookLoan::class);
        }

        if (!$value instanceof BookLoan) {
            return;
        }

        $member = $value->getMember();
        if ($member !== null && !$member->isActive()) {
            $this->context->buildViolation($constraint->inactiveMemberMessage)
                ->atPath('member')
                ->addViolation();
        }

        $book = $value->getBook();
        if ($book !== null && $value->getType() === 'loan' && $this->bookLoanRepository->hasActiveLoanForBook($book, $value->getId())) {
            $this->context->buildViolation($constraint->bookUnavailableMessage)
                ->atPath('book')
                ->addViolation();
        }

        if (
            $book !== null
            && $member !== null
            && $value->getType() === 'reservation'
            && $this->bookLoanRepository->hasActiveReservationForMemberAndBook($member, $book, $value->getId())
        ) {
            $this->context->buildViolation($constraint->duplicateReservationMessage)
                ->atPath('book')
                ->addViolation();
        }
    }
}
