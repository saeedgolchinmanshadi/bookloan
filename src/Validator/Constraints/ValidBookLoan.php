<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ValidBookLoan extends Constraint
{
    public string $inactiveMemberMessage = 'عضو انتخاب‌شده غیرفعال است و امکان ثبت امانت یا رزرو ندارد.';

    public string $bookUnavailableMessage = 'این کتاب در حال حاضر در اختیار عضو دیگری است و امکان ثبت امانت جدید وجود ندارد.';

    public string $duplicateReservationMessage = 'این عضو قبلاً برای این کتاب رزرو فعال دارد.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
