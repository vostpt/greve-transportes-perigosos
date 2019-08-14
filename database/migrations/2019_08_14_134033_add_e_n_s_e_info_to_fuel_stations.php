<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddENSEInfoToFuelStations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fuel_stations', function (Blueprint $table) {
            $table->string('ense_id', 100)->default('0');
            $table->string('ense_gasoline', 100)->default('0');
            $table->string('ense_diesel', 100)->default('0');
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
            $table->dropColumn('ense_id');
            $table->dropColumn('ense_gasoline');
            $table->dropColumn('ense_diesel');
        });
    }
}
