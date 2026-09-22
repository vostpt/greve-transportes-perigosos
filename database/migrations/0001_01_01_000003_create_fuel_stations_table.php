<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_stations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand')->index();
            $table->string('brand_management')->nullable()->index();
            $table->decimal('lat', 10, 7);
            $table->decimal('long', 10, 7);
            $table->boolean('repa')->default(false);
            $table->boolean('sell_gasoline')->default(false);
            $table->boolean('sell_diesel')->default(false);
            $table->boolean('sell_lpg')->default(false);
            $table->boolean('has_gasoline')->default(false);
            $table->boolean('has_diesel')->default(false);
            $table->boolean('has_lpg')->default(false);
            $table->string('district')->nullable()->index();
            $table->string('county')->nullable()->index();
            $table->string('ense_id')->nullable();
            $table->decimal('ense_gasoline', 5, 3)->nullable();
            $table->decimal('ense_diesel', 5, 3)->nullable();
            $table->string('source_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_stations');
    }
};
