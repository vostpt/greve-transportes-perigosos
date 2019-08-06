<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistrictsAndCountiesToFuelStations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->string('district', 100);
            $table->string('county', 100);
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
            $table->dropColumn('district');
            $table->dropColumn('county');
        });
    }
}
