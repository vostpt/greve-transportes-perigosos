<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\FuelStation;

use Illuminate\Console\Command;

class FuelStationsGetDistrictFromCounties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:districts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Fuel Stations District from County';

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
        $fuel_stations = FuelStation::all();
        $num_updated   = 0;
        $num_not_found = 0;
        foreach ($fuel_stations as $fuel_station) {
            $county = $fuel_station->county;
            if ($county != '') {
                if ($fuel_station->district == '') {
                    if ($county == 'Guimares') {
                        $county = 'Guimarães';
                    }
                    if ($county == 'Melgao') {
                        $county = 'Melgaço';
                    }
                    if ($county == 'Ofir') {
                        $county = 'Esposende';
                    }
                    if ($county == 'Taipas') {
                        $county = 'Guimarães';
                    }
                    if ($county == 'Açores') {
                        $district = 'Açores';
                    } else {
                        $json_id = \file_get_contents('https://api.vost.pt/v1/counties?search='.\urlencode($county));
                        $obj_id  = \json_decode($json_id, true);
                        echo($county);
                        $county_id     = $obj_id['data'][0]['id'];
                        $json_district = \file_get_contents('https://api.vost.pt/v1/counties/'.$county_id);
                        $obj_district  = \json_decode($json_district, true);
                        $district      = $obj_district['included'][0]['attributes']['name'];
                    }
                    $fuel_station->update(['district' => $district]);
                    $num_updated++;
                    \usleep(35000);
                }
            } else {
                $num_not_found++;
            }
        }
        echo('Updated: '.$num_updated.'; Not Found: '.$num_not_found);
    }
}
