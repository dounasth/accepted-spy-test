<?php

namespace App\Application\Actions;

use App\Application\Commands\CreateSpy;
use App\Application\Contracts\CreateSpyActionContract;
use App\Domain\Events\SpyCreated;
use App\Domain\Models\Spy;
use App\Domain\Repositories\SpyRepository;
use Illuminate\Validation\ValidationException;

class CreateSpyAction implements CreateSpyActionContract
{
    private SpyRepository $spyRepository;

    public function __construct(SpyRepository $repository)
    {
        $this->spyRepository = $repository;
    }

    public function execute(CreateSpy $command): Spy
    {
        // Check for existing spy with the same first and last name
        $existingSpy = $this->spyRepository->findByNameAndSurname($command->spy->getName()->firstName, $command->spy->getName()->lastName);
        if ($existingSpy) {
            throw ValidationException::withMessages([
                'name' => 'A spy with the same first and last name already exists.',
            ]);
        }

        $spy = new Spy(
            $command->spy->getName(),
            $command->spy->getAgency(),
            $command->spy->getCountryOfOperation(),
            $command->spy->getDateOfBirth(),
            $command->spy->getDateOfDeath()
        );

        $spy = $this->spyRepository->create($spy);
        event(new SpyCreated($spy));

        return $spy;
    }
}
