<?php

namespace App\Entity;

use App\Repository\BookLoanRepository;
use App\Validator\Constraints\ValidBookLoan;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: BookLoanRepository::class)]
#[ValidBookLoan]
class BookLoan
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bookLoans')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'انتخاب کتاب الزامی است.')]
    private ?Book $book = null;

    #[ORM\ManyToOne(inversedBy: 'bookLoans')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: 'انتخاب عضو الزامی است.')]
    private ?Member $member = null;

    #[ORM\Column(length: 20)]
    #[Assert\NotBlank(message: 'نوع درخواست الزامی است.')]
    #[Assert\Choice(choices: ['loan', 'reservation'], message: 'نوع درخواست نامعتبر است.')]
    private ?string $type = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'تاریخ ثبت الزامی است.')]
    private ?\DateTimeImmutable $borrowDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dueDate = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $returnedAt = null;

    #[Assert\Callback]
    public function validateDueDate(ExecutionContextInterface $context): void
    {
        if ($this->type === 'loan' && $this->dueDate === null) {
            $context->buildViolation('مهلت بازگشت برای امانت الزامی است.')
                ->atPath('dueDate')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;

        return $this;
    }

    public function getMember(): ?Member
    {
        return $this->member;
    }

    public function setMember(?Member $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getBorrowDate(): ?\DateTimeImmutable
    {
        return $this->borrowDate;
    }

    public function setBorrowDate(\DateTimeImmutable $borrowDate): static
    {
        $this->borrowDate = $borrowDate;

        return $this;
    }

    public function getDueDate(): ?\DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function setDueDate(?\DateTimeImmutable $dueDate): static
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getReturnedAt(): ?\DateTimeImmutable
    {
        return $this->returnedAt;
    }

    public function setReturnedAt(?\DateTimeImmutable $returnedAt): static
    {
        $this->returnedAt = $returnedAt;

        return $this;
    }
}
