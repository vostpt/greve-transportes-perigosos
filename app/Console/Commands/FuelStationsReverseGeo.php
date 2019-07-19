<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\FuelStation;

use Illuminate\Console\Command;

class FuelStationsReverseGeo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:reverseGeo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Human Like Location from MapBox with Reverse Geolocation';

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
        $api_key         = env('MAPBOX_API_FOR_REVERSEGEO', '');
        $fuel_stations   = FuelStation::all();
        $num_updated     = 0;
        $num_not_updated = 0;
        foreach ($fuel_stations as $fuel_station) {
            if ($fuel_station->district == '') {
                $url      = 'https://api.mapbox.com/geocoding/v5/mapbox.places/'.$fuel_station->long.','.$fuel_station->lat.'.json?access_token='.$api_key;
                $json     = \file_get_contents($url);
                $object   = \json_decode($json, true);
                $address  = $object['features'][0]['place_name'];
                $county   = $object['features'][0]['context'][0]['text'];
                $district = $object['features'][0]['context'][1]['text'];
                $info     = [
                    'address'  => $address,
                    'county'   => $county,
                    'district' => $district,
                ];
                $fuel_station->fill($info);
                $fuel_station->save();
                $num_updated++;
            } else {
                $num_not_updated++;
            }
        }
        echo('Updated: '.$num_updated.'; Not Updated: '.$num_not_updated);
    }
}
