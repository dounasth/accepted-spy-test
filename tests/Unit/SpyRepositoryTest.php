<?php

namespace Tests\Unit;

use App\Domain\Models\Spy;
use App\Domain\Repositories\SpyRepository;
use App\Domain\ValueObjects\Agency;
use App\Domain\ValueObjects\DateOfBirth;
use App\Domain\ValueObjects\Name;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_creates_a_spy()
    {
        $repository = app(SpyRepository::class);

        $spy = $repository->create(new Spy(
            name: new Name('John', 'Doe'),
            agency: Agency::CIA,
            countryOfOperation: 'USA',
            dateOfBirth: new DateOfBirth('1980-01-01')
        ));

        $this->assertDatabaseHas('spies', [
            'first_name' => 'John',
            'last_name' => 'Doe'
        ]);
    }

     public function test_enforces_unique_constraint_on_name_and_surname()
     {
         $repository = app(SpyRepository::class);
         $repository->create(new Spy(
             new Name('John', 'Doe'),
             Agency::CIA,
             'USA',
             new DateOfBirth('1980-01-01')
         ));

         $this->expectException(\Exception::class);
         $repository->create(new Spy(
             name: new Name('John', 'Doe'),
             agency: Agency::MI6,
             countryOfOperation: 'UK',
             dateOfBirth: new DateOfBirth('1985-05-15')
         ));
     }
}
