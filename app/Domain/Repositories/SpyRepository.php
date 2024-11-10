<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Spy;
use Illuminate\Support\Collection;

interface SpyRepository
{
    public function create(Spy $spy): Spy;

    public function findByNameAndSurname(string $firstName, string $lastName): ?Spy;

    public function getRandom(int $count): Collection;

}
