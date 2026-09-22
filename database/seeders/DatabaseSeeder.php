<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        DB::table('options')->upsert([
            [
                'name' => 'num_entries_required',
                'value' => '10',
                'description' => 'Número de entradas distintas por IP necessárias em 1h para promoção automática',
            ],
            [
                'name' => 'stress_lockdown',
                'value' => '0',
                'description' => 'Bloquear vistas de painel com queries pesadas quando em stress',
            ],
        ], ['name'], ['value', 'description']);
    }
}
