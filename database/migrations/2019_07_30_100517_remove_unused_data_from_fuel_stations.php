<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedDataFromFuelStations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->dropColumn('usage');
            $table->dropColumn('type');
            $table->dropColumn('district');
            $table->dropColumn('county');
            $table->dropColumn('address');
            $table->dropColumn('id_station');
            $table->string('repa', 100)->change();
            $table->string('source_id', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->string('usage');
            $table->string('type');
            $table->string('district');
            $table->string('county');
            $table->string('address');
            $table->unsignedBigInteger('id_station');
            $table->boolean('repa')->change();
            $table->dropColumn('source_id');
        });
    }
}
