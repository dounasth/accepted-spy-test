<?php

namespace App\Domain\Models;

use App\Domain\ValueObjects\Agency;
use App\Domain\ValueObjects\DateOfBirth;
use App\Domain\ValueObjects\DateOfDeath;
use App\Domain\ValueObjects\Name;
use Illuminate\Contracts\Support\Arrayable;

class Spy implements Arrayable
{
    private readonly Name $name;
    private Agency $agency;
    private string $countryOfOperation;
    private readonly DateOfBirth $dateOfBirth;
    private ?DateOfDeath $dateOfDeath;

    public function __construct(
        Name $name,
        Agency $agency,
        string $countryOfOperation,
        DateOfBirth $dateOfBirth,
        ?DateOfDeath $dateOfDeath = null
    ) {
        $this->name = $name;
        $this->agency = $agency;
        $this->countryOfOperation = $countryOfOperation;
        $this->dateOfBirth = $dateOfBirth;
        $this->dateOfDeath = $dateOfDeath;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getAgency(): Agency
    {
        return $this->agency;
    }

    public function setAgency(Agency $agency): void
    {
        //  validate or add business rules for changing the agency
        if ($agency === $this->agency) {
            throw new \InvalidArgumentException("New agency must be different from the current agency.");
        }

        $this->agency = $agency;
    }

    public function getCountryOfOperation(): ?string
    {
        return $this->countryOfOperation;
    }

    public function setCountryOfOperation(string $countryOfOperation): void
    {
        // Here there may be no reason for validation similar to changing agency
        $this->countryOfOperation = $countryOfOperation;
    }

    public function getDateOfBirth(): DateOfBirth
    {
        return $this->dateOfBirth;
    }

    public function getDateOfDeath(): ?DateOfDeath
    {
        return $this->dateOfDeath;
    }

    public function setDateOfDeath(?DateOfDeath $dateOfDeath): void
    {
        $this->dateOfDeath = $dateOfDeath;
    }


    public function toArray(): array
    {
        return [
            'full_name' => $this->getName()->fullName(),
            'agency' => $this->getAgency(),
            'country_of_operation' => $this->getCountryOfOperation(),
            'date_of_birth' => $this->getDateOfBirth(),
            'date_of_death' => $this->getDateOfDeath(),
        ];
    }
}
