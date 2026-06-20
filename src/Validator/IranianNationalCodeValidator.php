<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IranianNationalCodeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!preg_match('/^[0-9]{10}$/', $value)) {
            $this->buildViolation($constraint, $value);
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            if (preg_match('/^' . $i . '{10}$/', $value)) {
                $this->buildViolation($constraint, $value);
                return;
            }
        }

        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += ((int) $value[$i]) * (10 - $i);
        }

        $remainder = $sum % 11;
        $controlDigit = (int) $value[9];

        $isValid = ($remainder < 2 && $controlDigit === $remainder) ||
            ($remainder >= 2 && $controlDigit === (11 - $remainder));

        if (!$isValid) {
            $this->buildViolation($constraint, $value);
        }
    }

    private function buildViolation(Constraint $constraint, string $value): void
    {
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
