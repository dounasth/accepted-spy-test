<?php

namespace App\Domain\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

class DateOfBirth extends DateTimeImmutable
{
    public function __construct(string $date)
    {
        parent::__construct($date);

        // Enforce a business rule: Birth date cannot be in the future
        if ($this > new DateTimeImmutable()) {
            throw new InvalidArgumentException("Date of birth cannot be in the future.");
        }
    }

    public function format(string $format = 'Y-m-d'): string
    {
        return parent::format($format);
    }

    public function isSameDay(DateOfBirth $other): bool
    {
        return $this->format('Y-m-d') === $other->format('Y-m-d');
    }
}
