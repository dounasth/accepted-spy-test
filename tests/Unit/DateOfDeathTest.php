<?php

namespace Tests\Unit;

use App\Domain\ValueObjects\DateOfBirth;
use App\Domain\ValueObjects\DateOfDeath;
use InvalidArgumentException;
use Tests\TestCase;

class DateOfDeathTest extends TestCase
{
    /**
     * @throws \DateMalformedStringException
     */
    public function test_rejects_date_of_death_before_date_of_birth()
    {
        $this->expectException(InvalidArgumentException::class);

        new DateOfDeath('1970-01-01', new DateOfBirth('1980-01-01'));
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function test_accepts_valid_date_of_death_after_date_of_birth()
    {
        $dateOfDeath = new DateOfDeath('2000-01-01', new DateOfBirth('1980-01-01'));

        $this->assertInstanceOf(DateOfDeath::class, $dateOfDeath);
    }
}
