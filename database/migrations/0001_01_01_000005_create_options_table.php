<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('options', function (Blueprint $table) {
            $table->string('name', 100)->primary();
            $table->string('value', 255);
            $table->string('description', 255);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('options');
    }
};
