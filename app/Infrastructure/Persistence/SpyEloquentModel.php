<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Spy;
use App\Domain\ValueObjects\Agency;
use App\Domain\ValueObjects\DateOfBirth;
use App\Domain\ValueObjects\DateOfDeath;
use App\Domain\ValueObjects\Name;
use Database\Factories\SpyFactory;
use DateMalformedStringException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpyEloquentModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'spies';

    protected $fillable = [
        'first_name',
        'last_name',
        'agency',
        'country_of_operation',
        'date_of_birth',
        'date_of_death',
    ];

    protected $dates = [
        'date_of_birth',
        'date_of_death',
        'deleted_at', // For soft deletes
    ];

    /**
     * Casting attributes to specific types.
     */
    protected $casts = [
        'date_of_birth' => 'date:Y-m-d',
        'date_of_death' => 'date:Y-m-d',
    ];

    /**
     * Accessor for Full Name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return SpyFactory::new();
    }

    /**
     * Convert an Eloquent model instance to a Domain model instance.
     *
     * @return Spy
     * @throws DateMalformedStringException
     */
    public function toDomainModel(): Spy
    {
        return new Spy(
            new Name($this->first_name, $this->last_name),
            Agency::from($this->agency),
            $this->country_of_operation,
            new DateOfBirth($this->date_of_birth),
            $this->date_of_death ? new DateOfDeath($this->date_of_death, new DateOfBirth($this->date_of_birth)) : null
        );
    }
}
