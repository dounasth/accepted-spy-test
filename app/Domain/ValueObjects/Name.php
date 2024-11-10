<?php

namespace App\Domain\ValueObjects;

class Name
{
    public readonly string $firstName;
    public readonly string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function fullName(): string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function equals(Name $other): bool
    {
        return $this->firstName === $other->firstName && $this->lastName === $other->lastName;
    }
}
