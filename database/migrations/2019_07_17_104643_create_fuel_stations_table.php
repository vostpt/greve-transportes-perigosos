<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuelStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_station');
            $table->string('name');
            $table->string('brand');
            $table->string('usage');
            $table->string('type');
            $table->string('district');
            $table->string('county');
            $table->string('address');
            $table->decimal('long', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->boolean('repa');
            $table->boolean('sell_gasoline');
            $table->boolean('sell_diesel');
            $table->boolean('sell_lpg');
            $table->boolean('has_gasoline');
            $table->boolean('has_diesel');
            $table->boolean('has_lpg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuel_stations');
    }
}
