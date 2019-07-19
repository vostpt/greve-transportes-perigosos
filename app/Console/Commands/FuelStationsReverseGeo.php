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
        //http://api.mapbox.com/geocoding/v5/mapbox.places/-8.19017,41.35664.json?access_token=sk.eyJ1IjoiY290ZW1lcm8iLCJhIjoiY2p5OG15Ymw2MDlhbDNlbjFyNXJmMmYzMyJ9.w_Ed_VWcujJ7lV7nP1RY3g
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
