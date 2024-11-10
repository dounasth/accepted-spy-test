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

    public function paginate(int $perPage = 10, array $filters = [], array $sort = []): mixed
    {
        $query = SpyEloquentModel::query();

        // Apply age filter based on birth year
        $minAge = $maxAge = null;
        if (isset($filters['age'])) {
            $minAge = $filters['age'];
            $maxAge = $filters['age'] + 1;

        } elseif (isset($filters['age_range'])) {
            [$minAge, $maxAge] = $filters['age_range'];
        }

        if ($minAge && $maxAge) {
            $query->whereDate('date_of_birth', '<=', now()->subYears($minAge)->format('Y-m-d'))
                ->whereDate('date_of_birth', '>=', now()->subYears($maxAge)->format('Y-m-d'));
        }


        // Filter by name and surname
        if (isset($filters['first_name'])) {
            $query->where('first_name', 'like', "%{$filters['first_name']}%");
        }

        if (isset($filters['last_name'])) {
            $query->where('last_name', 'like', "%{$filters['last_name']}%");
        }


        // Apply sorting
        foreach ($sort as $field => $direction) {
            if ($field === 'full_name') {
                $query->orderBy('first_name', $direction)->orderBy('last_name', $direction);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        $paginatedSpies = $query->paginate($perPage);

        // Map each Eloquent model to a domain model (Spy)
        $paginatedSpies->getCollection()->transform(function (SpyEloquentModel $spyModel) {
            return $spyModel->toDomainModel();
        });
        return $paginatedSpies;
    }
}
