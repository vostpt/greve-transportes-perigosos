<?php

namespace App\Console\Commands;

use App\FuelStation;
use Illuminate\Console\Command;

class MapEnseIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:map-ense-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'map ense id to fuel_stations table ids';

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
        $json = \file_get_contents('https://pastebin.com/raw/ekJtSrA6');
        $obj  = \json_decode($json, true);
        foreach ($obj['result'] as $point) {

            $fuel_station = FuelStation::where('name', $point['closest_vost']['name'])->get()->first();

            if ($fuel_station) {
                $data = [
                    'ense_id' => $point['ense_id'],
                ];
                $fuel_station->fill($data);
                $fuel_station->save();
            }
        }
    }
}
