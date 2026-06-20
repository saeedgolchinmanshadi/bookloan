<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class IranianNationalCode extends Constraint
{
    public string $message = 'کد ملی وارد شده ({{ value }}) معتبر نیست.';
}
