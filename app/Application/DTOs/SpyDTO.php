<?php

namespace App\Application\DTOs;

use App\Domain\Models\Spy;
use Illuminate\Contracts\Support\Arrayable;

class SpyDTO implements Arrayable
{
    public string $fullName;
    public string $agency;
    public string $countryOfOperation;
    public string $dateOfBirth;
    public ?string $dateOfDeath;

    public function __construct(Spy $spy)
    {
        $this->fullName = $spy->getName()->fullName();
        $this->agency = $spy->getAgency()->value;
        $this->countryOfOperation = $spy->getCountryOfOperation();
        $this->dateOfBirth = $spy->getDateOfBirth()->format();
        $this->dateOfDeath = $spy->getDateOfDeath()?->format();
    }

    public function toArray(): array
    {
        return [
            'full_name' => $this->fullName,
            'agency' => $this->agency,
            'country_of_operation' => $this->countryOfOperation,
            'date_of_birth' => $this->dateOfBirth,
            'date_of_death' => $this->dateOfDeath,
        ];
    }
}
