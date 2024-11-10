<?php

namespace Tests\Unit;

use App\Domain\ValueObjects\DateOfBirth;
use InvalidArgumentException;
use Tests\TestCase;

class DateOfBirthTest extends TestCase
{
    public function test_rejects_future_date_of_birth()
    {
        $this->expectException(InvalidArgumentException::class);

        new DateOfBirth('3000-01-01');
    }
}
