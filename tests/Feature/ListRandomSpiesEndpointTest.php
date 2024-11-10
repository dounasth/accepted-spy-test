<?php

namespace Tests\Feature;

use App\Infrastructure\Persistence\SpyEloquentModel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class ListRandomSpiesEndpointTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database with spies for testing
        SpyEloquentModel::factory()->count(20)->create();
    }

    public function test_returns_five_random_spies_with_different_entries_across_requests()
    {
        // First request to the random spies endpoint
        $firstResponse = $this->getJson('/api/spies/random');
        $this->assertRandomSpiesResponse($firstResponse);

        // Extract names from the first response
        $firstSpyIds = collect($firstResponse->json('data'))->pluck('full_name')->toArray();

        // Second request to the random spies endpoint
        $secondResponse = $this->getJson('/api/spies/random');
        $secondResponse->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'full_name',
                        'agency',
                        'country_of_operation',
                        'date_of_birth',
                        'date_of_death',
                    ],
                ],
            ]);;

        // Extract IDs from the second response
        $secondSpyIds = collect($secondResponse->json('data'))->pluck('full_name')->toArray();

        // Ensure the two sets of IDs are not identical
        $this->assertNotEquals($firstSpyIds, $secondSpyIds, 'The random spies endpoint returned the same set of spies for both requests.');
    }

    public function test_rate_limiting_for_random_spies_endpoint()
    {
        RateLimiter::for('api', function () {
            return Limit::perMinute(10);
        });
        // Make 10 requests, all should succeed
        for ($i = 0; $i < 10; $i++) {
            $response = $this->getJson('/api/spies/random');
            $response->assertStatus(200);
        }

        // Make one additional request to exceed the rate limit
        $response = $this->getJson('/api/spies/random');
        $response->assertStatus(429); // Too Many Requests
    }

    private function assertRandomSpiesResponse($response)
    {
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'full_name',
                        'agency',
                        'country_of_operation',
                        'date_of_birth',
                        'date_of_death',
                    ],
                ],
            ]);
    }
}
