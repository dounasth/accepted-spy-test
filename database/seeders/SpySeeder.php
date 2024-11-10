<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\SpyEloquentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SpyEloquentModel::truncate();
        SpyEloquentModel::factory()->count(50)->create();
    }
}
