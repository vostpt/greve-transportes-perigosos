<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fuel_station')->constrained('fuel_stations')->cascadeOnDelete();
            $table->boolean('has_gasoline');
            $table->boolean('has_diesel');
            $table->boolean('has_lpg');
            $table->boolean('used')->default(false)->index();
            $table->string('ip', 45)->nullable()->index();
            $table->timestamps();

            $table->index(['fuel_station', 'used', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
