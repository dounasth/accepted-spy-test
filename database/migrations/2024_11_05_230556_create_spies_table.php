<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('spies', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->enum('agency', ['CIA', 'MI6', 'KGB']);
            $table->string('country_of_operation', 255);
            $table->date('date_of_birth');
            $table->date('date_of_death')->nullable();
            $table->softDeletes(); // Adds deleted_at for soft deletion
            $table->timestamps();  // Adds created_at and updated_at
            $table->unique(['first_name', 'last_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spies');
    }
};
