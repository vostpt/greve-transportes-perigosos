<?php
declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;

class CreateOptionsValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('options')->insert(
            [
                'name'        => 'num_entries_required',
                'value'       => '10',
                'description' => 'Número de entradas necessárias para validação automática',
            ]
        );
        DB::table('options')->insert(
            [
                'name'        => 'stress_lockdown',
                'value'       => 0,
                'description' => 'Bloquear acesso a partes com processamento elevado',
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('options')->truncate();
    }
}
