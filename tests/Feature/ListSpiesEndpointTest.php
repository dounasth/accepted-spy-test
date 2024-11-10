<?php

namespace Tests\Feature;

use App\Domain\Models\Spy;
use App\Domain\ValueObjects\Agency;
use App\Infrastructure\Persistence\SpyEloquentModel;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListSpiesEndpointTest extends TestCase
{
    use RefreshDatabase;

    private $list_results_structure = [
        'current_page',
        'data' => [
            '*' => [
                'full_name',
                'agency',
                'country_of_operation',
                'date_of_birth',
            ],
        ],
        'first_page_url',
        'from',
        'last_page',
        'last_page_url',
        'links',
        'next_page_url',
        'path',
        'per_page',
        'prev_page_url',
        'to',
        'total',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        SpyEloquentModel::factory()->count(15)->create();
    }

    public function test_paginates_spies_successfully()
    {
        $response = $this->getJson('/api/spies?per_page=10');

        $response->assertStatus(200)
            ->assertJsonStructure($this->list_results_structure);

        $this->assertCount(10, $response->json('data')); // Assert that 10 spies are returned per page
    }

    public function test_sorts_spies_by_full_name_asc()
    {
        $response = $this->getJson('/api/spies?sort=full_name');

        $response->assertStatus(200);
        $spies = $response->json('data');

        // Check if spies are sorted alphabetically by full name
        $this->assertTrue($spies[0]['full_name'] <= $spies[1]['full_name']);
    }

    public function test_sorts_spies_by_full_name_desc()
    {
        $response = $this->getJson('/api/spies?sort=-full_name');

        $response->assertStatus(200);
        $spies = $response->json('data');

        // Check if spies are sorted alphabetically by full name
        $this->assertTrue($spies[0]['full_name'] >= $spies[1]['full_name']);
    }

    public function test_filters_spies_by_exact_age()
    {
        $age = 30;
        $response = $this->getJson('/api/spies?age=' . $age);

        $response->assertStatus(200)
            ->assertJsonStructure($this->list_results_structure);

        foreach ($response->json('data') as $spy) {
            $this->assertTrue(
                Carbon::createFromFormat('Y-m-d', $spy['date_of_birth'])->between(
                    now()->subYears($age),
                    now()->subYears($age + 1)
                )
            );
        }
    }

    public function test_filters_spies_by_age_range()
    {
        $from = 25;
        $to = 35;
        $response = $this->getJson("/api/spies?age_range={$from}-{$to}");

        $response->assertStatus(200)
            ->assertJsonStructure($this->list_results_structure);

        foreach ($response->json('data') as $spy) {
            $this->assertTrue(
                Carbon::createFromFormat('Y-m-d', $spy['date_of_birth'])->between(
                    now()->subYears($from),
                    now()->subYears($to)
                )
            );
        }
    }

    public function test_filters_spies_by_name_and_surname()
    {
        $response = $this->getJson('/api/spies?name=John&surname=Doe');

        $response->assertStatus(200)
            ->assertJsonStructure($this->list_results_structure);

        $this->assertGreaterThanOrEqual(count($response->json('data')), 1);

        foreach ($response->json('data') as $spy) {
            $this->assertStringContainsString('John', $spy['full_name']);
            $this->assertStringContainsString('Doe', $spy['full_name']);
        }
    }

    public function test_returns_error_for_unsupported_filters()
    {
        $response = $this->getJson('/api/spies?age_range=10-');
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'age_range',
                ],
            ]);

        $response = $this->getJson('/api/spies?age=thirty');
        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'age',
                ],
            ]);
    }

    public function test_returns_error_for_unsupported_sorting()
    {
        //  birthdate is not a valid sort field
        $response = $this->getJson('/api/spies?sort=full_name,-birthdate');
        $response->assertStatus(422)
            ->assertJsonStructure([
                'error',
            ]);
    }

}
