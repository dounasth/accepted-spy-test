<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Spy;
use App\Domain\Repositories\SpyRepository;
use Illuminate\Support\Collection;

class EloquentSpyRepository implements SpyRepository
{
    public function create(Spy $spy): Spy
    {
        $spyEloquent = new SpyEloquentModel();
        $spyEloquent->first_name = $spy->getName()->firstName;
        $spyEloquent->last_name = $spy->getName()->lastName;
        $spyEloquent->agency = $spy->getAgency()->value;
        $spyEloquent->country_of_operation = $spy->getCountryOfOperation();
        $spyEloquent->date_of_birth = $spy->getDateOfBirth()->format();
        $spyEloquent->date_of_death = $spy->getDateOfDeath()?->format();
        $spyEloquent->save();

        return $spyEloquent->toDomainModel();
    }

    public function findByNameAndSurname(string $firstName, string $lastName): ?Spy
    {
        $spyEloquent = SpyEloquentModel::where('first_name', $firstName)
            ->where('last_name', $lastName)
            ->first();

        return $spyEloquent ? $spyEloquent->toDomainModel() : null;
    }

    public function getRandom(int $count): Collection
    {
        $spyEloquents = SpyEloquentModel::inRandomOrder()->limit($count)->get();
        return $spyEloquents->map(fn($spyEloquent) => $spyEloquent->toDomainModel());
    }
}
