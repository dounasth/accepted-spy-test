<?php

namespace App\Infrastructure\Http\Controllers\Api;

use App\Application\Commands\CreateSpy;
use App\Application\Contracts\CreateSpyActionContract;
use App\Application\Contracts\ListSpiesQueryContract;
use App\Application\Queries\ListSpiesQuery;
use App\Domain\Models\Spy;
use App\Domain\Repositories\SpyRepository;
use App\Domain\ValueObjects\Agency;
use App\Domain\ValueObjects\DateOfBirth;
use App\Domain\ValueObjects\DateOfDeath;
use App\Domain\ValueObjects\Name;
use App\Infrastructure\Http\Requests\ListSpiesRequest;
use App\Infrastructure\Http\Requests\StoreSpyRequest;
use DateMalformedStringException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;

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

        return response()->json([
            'message' => 'Spy created successfully',
            'data' => $spy->toArray()
        ], 201);
    }

    /**
     * Get a list of random spies.
     *
     * @param SpyRepository $repository
     * @return JsonResponse
     */
    public function random(SpyRepository $repository): JsonResponse
    {
        // Fetch 5 random spies
        $randomSpies = $repository->getRandom(5);
        $randomSpies = $randomSpies->map(fn(Spy $spy) => ($spy->toArray()));

        return response()->json([
            'data' => $randomSpies,
        ]);
    }

    /**
     * Get a paginated list of spies with optional sorting and filtering.
     *
     * @param ListSpiesRequest $request
     * @param ListSpiesQueryContract $query
     * @return JsonResponse
     */
    public function index(ListSpiesRequest $request, ListSpiesQueryContract $query): JsonResponse
    {
        try {
            $spies = $query
                ->setPerPage($request->input('per_page', 10))
                ->setFilters([
                    'age' => $request->input('age'),
                    'age_range' => $request->input('age_range') ? explode('-', $request->input('age_range')) : null,
                    'first_name' => $request->input('name'),
                    'last_name' => $request->input('surname'),
                ])
                ->setSort($request->input('sort', 'full_name'))
                ->execute();

            return response()->json($spies);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

}
