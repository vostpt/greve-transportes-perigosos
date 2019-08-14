<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\FuelStation;
use Illuminate\Console\Command;

class FuelStationsUpdateFromENSE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:ense';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get information for REPA from ENSE';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $json = \file_get_contents('http://localhost:3000');
        $obj  = \json_decode($json, true);
        foreach ($obj as $ense_station) {
            $fuel_station = FuelStation::where('ense_id', [$ense_station])->get()->first();
            if ($fuel_station) {
                $data = [
                    'ense_gasoline' => $ense_station['Volume disponível: Gasolinas (Última atualização)'],
                    'ense_diesel'   => $ense_station['Volume disponível: Gasóleos (Última atualização)'],
                ];
                $fuel_station->fill($data);
                $fuel_station->save();
            }
        }
    }
}
