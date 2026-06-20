<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\IranianNationalCode;
use App\Repository\MemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[UniqueEntity(fields: ['nationalCode'], message: 'عضوی با این کد ملی قبلاً در سیستم ثبت شده است.')]
#[UniqueEntity(fields: ['mobile'], message: 'عضوی با این شماره تلفن همراه قبلاً در سیستم ثبت شده است.')]
class Member
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'وارد کردن نام الزامی است.')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'وارد کردن نام خانوادگی الزامی است.')]
    private ?string $lastName = null;

    #[ORM\Column(length: 10, unique: true)]
    #[Assert\NotBlank(message: 'وارد کردن کد ملی الزامی است.')]
    #[IranianNationalCode()]
    private ?string $nationalCode = null;

    #[ORM\Column(length: 11, unique: true)]
    #[Assert\NotBlank(message: 'وارد کردن تلفن همراه الزامی است.')]
    #[Assert\Regex(
        pattern: '/^09[0-9]{9}$/',
        message: 'شماره موبایل باید 11 رقم باشد و با 09 شروع شود.'
    )]
    private ?string $mobile = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    /**
     * @var Collection<int, BookLoan>
     */
    #[ORM\OneToMany(targetEntity: BookLoan::class, mappedBy: 'member')]
    private Collection $bookLoans;

    public function __construct()
    {
        $this->bookLoans = new ArrayCollection();
        $this->isActive = true;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getNationalCode(): ?string
    {
        return $this->nationalCode;
    }

    public function setNationalCode(string $nationalCode): static
    {
        $this->nationalCode = $nationalCode;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, BookLoan>
     */
    public function getBookLoans(): Collection
    {
        return $this->bookLoans;
    }

    public function addBookLoan(BookLoan $bookLoan): static
    {
        if (!$this->bookLoans->contains($bookLoan)) {
            $this->bookLoans->add($bookLoan);
            $bookLoan->setMember($this);
        }

        return $this;
    }

    public function removeBookLoan(BookLoan $bookLoan): static
    {
        if ($this->bookLoans->removeElement($bookLoan)) {
            // set the owning side to null (unless already changed)
            if ($bookLoan->getMember() === $this) {
                $bookLoan->setMember(null);
            }
        }

        return $this;
    }
}
