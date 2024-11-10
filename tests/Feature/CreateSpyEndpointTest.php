<?php

namespace Tests\Feature;

use App\Domain\ValueObjects\Agency;
use App\Domain\Events\SpyCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateSpyEndpointTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_creates_a_spy_successfully(): void
    {
        // Fake the SpyCreated event
        Event::fake([SpyCreated::class]);

        // Prepare the request data
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'agency' => Agency::CIA->value,
            'country_of_operation' => 'USA',
            'date_of_birth' => '1980-01-01',
        ];

        // Make the request as an authenticated user
        $response = $this->actingAs(User::all()->first()) // Replace with actual user creation if necessary
        ->postJson('/api/spies', $data);

        // Assert the spy was created and the response is successful
        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'full_name',
                    'agency',
                    'country_of_operation',
                    'date_of_birth',
                    'date_of_death',
                ],
            ]);

        // Ensure the event was dispatched
        Event::assertDispatched(SpyCreated::class);
    }

    public function test_requires_authentication_to_create_a_spy()
    {
        // Prepare the request data
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'agency' => Agency::CIA->value,
            'country_of_operation' => 'USA',
            'date_of_birth' => '1980-01-01',
        ];

        // Make the request without authentication
        $response = $this->postJson('/api/spies', $data);

        // Assert the request is unauthorized
        $response->assertStatus(401);
    }

    public function test_validates_required_fields()
    {
        // Attempt to create a spy with missing required fields
        $response = $this->actingAs(User::all()->first())
            ->postJson('/api/spies', []);

        // Assert validation errors are returned for missing fields
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name', 'last_name', 'agency', 'country_of_operation', 'date_of_birth']);
    }

    public function test_validates_invalid_agency()
    {
        // Prepare the request data with an invalid agency
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'agency' => 'MI7',
            'country_of_operation' => 'USA',
            'date_of_birth' => '1985-05-15',
        ];

        // Make the request as an authenticated user
        $response = $this->actingAs(User::all()->first())
            ->postJson('/api/spies', $data);

        // Assert validation error on agency
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['agency']);
    }

    public function test_rejects_duplicate_spy_creation()
    {
        // Create an initial spy
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'agency' => Agency::CIA->value,
            'country_of_operation' => 'USA',
            'date_of_birth' => '1980-01-01',
        ];

        $this->actingAs(User::all()->first())->postJson('/api/spies', $data);

        // Try creating the same spy again
        $response = $this->actingAs(User::all()->first())
            ->postJson('/api/spies', $data);

        // Assert duplicate entry is rejected
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['first_name']);
    }

    // Helper to create a user
    private function createUser()
    {
        return \App\Models\User::factory()->create();
    }
}
