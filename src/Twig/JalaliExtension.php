<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Morilog\Jalali\Jalalian;

class JalaliExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('jalali', [$this, 'formatJalali']),
        ];
    }

    public function formatJalali(?\DateTimeInterface $dateTime, string $format = 'Y/m/d'): string
    {
        if (!$dateTime) {
            return '-';
        }

        $timezone = new \DateTimeZone('Asia/Tehran');
        $localDateTime = \DateTimeImmutable::createFromInterface($dateTime)->setTimezone($timezone);

        return Jalalian::fromDateTime($localDateTime)->format($format);
    }
}
