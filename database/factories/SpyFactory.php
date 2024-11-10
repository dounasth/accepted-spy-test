<?php

namespace Database\Factories;

use App\Domain\ValueObjects\Agency;
use App\Domain\ValueObjects\DateOfBirth;
use App\Domain\ValueObjects\DateOfDeath;
use App\Infrastructure\Persistence\SpyEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpyFactory extends Factory
{
    protected $model = SpyEloquentModel::class;

    public function definition(): array
    {
        $agencies = Agency::values();

        // Generate date of birth
        $dateOfBirth = new DateOfBirth($this->faker->dateTimeBetween('-80 years', '-30 years')->format('Y-m-d'));

        // Conditionally generate date of death, ensuring it’s after date of birth
        $death = $this->faker->optional(0.3)->dateTimeBetween($dateOfBirth->format('Y-m-d'), 'now');
        if ($death) {
            $dateOfDeath = new DateOfDeath($death->format('Y-m-d'), $dateOfBirth);
        }
        else $dateOfDeath = null;

        return [
            'first_name' => $this->faker->unique()->firstName, // Unique first name
            'last_name' => $this->faker->unique()->lastName,   // Unique last name
            'agency' => $this->faker->randomElement($agencies),
            'country_of_operation' => $this->faker->country,
            'date_of_birth' => $dateOfBirth->format('Y-m-d'),
            'date_of_death' => $dateOfDeath ? $dateOfDeath->format('Y-m-d') : null,
        ];
    }
}
