<?php

namespace App\Domain\ValueObjects;

use DateTimeImmutable;
use InvalidArgumentException;

class DateOfDeath extends DateTimeImmutable
{

    public function __construct(string $date, DateTimeImmutable $dateOfBirth)
    {
        parent::__construct($date);

        if ($date) {
            // Rule 1: Date of death cannot be in the future
            if ($this > new DateTimeImmutable()) {
                throw new InvalidArgumentException("Date of death cannot be in the future.");
            }

            // Rule 2: Date of death should be after the date of birth
            if (!$this->isAfter($dateOfBirth)) {
                throw new InvalidArgumentException("Date of death must be after the date of birth.");
            }
        }
    }

    public function format(string $format = 'Y-m-d'): string
    {
        return parent::format($format);
    }

    public function isAfter(DateTimeImmutable $other): bool
    {
        return $this > $other;
    }
}
