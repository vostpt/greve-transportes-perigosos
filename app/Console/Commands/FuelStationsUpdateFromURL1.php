<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\FuelStation;
use Illuminate\Console\Command;

class FuelStationsUpdateFromURL1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:updateFromURL:1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Fuel Stations from URL (Source #2)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function str_gettsv($input, $delimiter = "\t", $enclosure = '"', $escape = '\\')
    {
        return \str_getcsv($input, "\t");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tsv            = \file_get_contents(env('FUELSTATIONS_SOURCE2'));
        $fetched_object = \array_map([$this, 'str_gettsv'], \explode("\n", $tsv));
        unset($fetched_object[0]);
        $num_found     = 0;
        $num_not_found = 0;
        foreach ($fetched_object as $entry) {
            $lpg          = ($entry[3] == 'Sim');
            $tolerance    = 0.00001;
            $long         = $entry[11];
            $lat          = $entry[12];
            $minLong      = $long - $tolerance;
            $maxLong      = $long + $tolerance;
            $minLat       = $lat - $tolerance;
            $maxLat       = $lat + $tolerance;
            $fuel_station = FuelStation::whereBetween('long', [$minLong, $maxLong])->whereBetween('lat', [$minLat, $maxLat])->get()->first();
            if ($fuel_station) {
                $fuel_station->update(['sell_lpg' => $lpg]);
                $num_found++;
            } else {
                $num_not_found++;
            }
        }
        echo('Found: '.$num_found.'; Not Found: '.$num_not_found);
    }
}
