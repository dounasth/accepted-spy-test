<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Application\Commands\CreateSpy;
use App\Application\Contracts\CreateSpyActionContract;
use App\Application\DTOs\SpyDTO;
use App\Domain\Models\Spy;
use App\Domain\ValueObjects\Agency;
use App\Domain\ValueObjects\DateOfBirth;
use App\Domain\ValueObjects\DateOfDeath;
use App\Domain\ValueObjects\Name;
use App\Infrastructure\Http\Requests\StoreSpyRequest;
use DateMalformedStringException;
use Illuminate\Http\JsonResponse;

class SpyController
{
    /**
     * @param StoreSpyRequest $request
     * @param CreateSpyActionContract $createSpyAction
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    public function store(StoreSpyRequest $request, CreateSpyActionContract $createSpyAction): JsonResponse
    {
        // Get validated data
        $validatedData = $request->validated();

        $date_of_birth = new DateOfBirth($validatedData['date_of_birth']);

        // Create the CreateSpy command with validated data
        $command = new CreateSpy(new Spy(
                new Name($validatedData['first_name'], $validatedData['last_name']),
                Agency::from($validatedData['agency']),
                $validatedData['country_of_operation'],
                $date_of_birth,
                isset($validatedData['date_of_death']) ? new DateOfDeath($validatedData['date_of_death'], $date_of_birth) : null
            )
        );

        // Execute the action to create the spy

        $spy = $createSpyAction->execute($command);

        // Wrap the response in SpyDTO
        $spyDTO = new SpyDTO($spy);

        return response()->json([
            'message' => 'Spy created successfully',
            'data' => $spyDTO->toArray()
        ], 201);
    }

}
