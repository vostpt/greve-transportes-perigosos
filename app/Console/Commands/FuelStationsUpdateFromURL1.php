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
    protected $description = 'Parse Fuel Stations from URL (Source #1)';

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
        $tsv            = \file_get_contents(env('FUELSTATIONS_SOURCE1'));
        $fetched_object = \array_map([$this, 'str_gettsv'], \explode("\n", $tsv));
        unset($fetched_object[0]);
        $num_updated = 0;
        $num_created = 0;
        foreach ($fetched_object as $key => $entry) {
            $repa      = $entry[0];
            $source_id = $entry[1];
            $line_id   = 'line-'.$key;
            if ($source_id == '0') {
                $source_id = $line_id;
            }
            $brand = $entry[3];
            $name  = $entry[4];
            if ($name == '') {
                $name = $entry[8];
            }
            $lpg  = ($entry[5] == 'Sim');
            $long = $entry[11];
            $lat  = $entry[12];
            $data = [
                'repa'          => $repa,
                'source_id'     => $source_id,
                'brand'         => $brand,
                'name'          => $name,
                'sell_gasoline' => true,
                'sell_diesel'   => true,
                'sell_lpg'      => $lpg,
                'has_gasoline'  => true,
                'has_diesel'    => true,
                'has_lpg'       => $lpg,
                'long'          => $long,
                'lat'           => $lat,
            ];
            $fuel_station = FuelStation::where('source_id', [$source_id])->get()->first();
            if (! $fuel_station) {
                $fuel_station = FuelStation::where('source_id', [$line_id])->get()->first();
            }
            if ($fuel_station) {
                $fuel_station->update($data);
                $num_updated++;
            } else {
                $fuel_station = new FuelStation();
                $fuel_station->fill($data);
                $fuel_station->save();
                $num_created++;
            }
        }
        echo('Created: '.$num_created.'; Updated: '.$num_updated);
    }
}
