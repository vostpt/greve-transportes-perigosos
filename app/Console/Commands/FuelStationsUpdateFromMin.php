<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\FuelStation;

use Illuminate\Console\Command;

class FuelStationsUpdateFromMin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:updateFromMin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Fuel Stations from Min Economica';

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
        $parsing_url          = 'https://agserver.sg.min-economia.pt/arcgis/rest/services/DGEG/PACR/MapServer/0/query?f=json&where=1%3D1&returnGeometry=true&spatialRel=esriSpatialRelIntersects&geometry=%7B%22xmin%22%3A-3077880.2559411256%2C%22ymin%22%3A4028914.1124504628%2C%22xmax%22%3A1031374.3846688571%2C%22ymax%22%3A5388881.719699957%2C%22spatialReference%22%3A%7B%22wkid%22%3A102100%7D%7D&geometryType=esriGeometryEnvelope&inSR=102100&outFields=CodInterno%2CMarca%2CnLatitude%2CnLongitude&orderByFields=CodInterno%20ASC&outSR=102100';
        $json                 = \file_get_contents($parsing_url);
        $object               = \json_decode($json, true);
        $num_of_fuel_stations = \count($object['features']);
        $num_success          = 0;
        $num_fails            = 0;
        for ($i = 1; $i < $num_of_fuel_stations; $i++) {
            $row   = $object['features'][$i];
            $brand = $row['attributes']['Marca'];
            if ($brand == null) {
                $brand = '';
            }
            $information = [
                'id_station'    => $row['attributes']['CodInterno'],
                'name'          => '',
                'brand'         => $brand,
                'usage'         => '',
                'type'          => '',
                'district'      => '',
                'county'        => '',
                'address'       => '',
                'lat'           => $row['attributes']['nLatitude'],
                'long'          => $row['attributes']['nLongitude'],
                'repa'          => false,
                'sell_gasoline' => true,
                'sell_diesel'   => true,
                'sell_lpg'      => false,
                'has_gasoline'  => true,
                'has_diesel'    => true,
                'has_lpg'       => false,
            ];
            if (FuelStation::where('id_station', '=', $row['attributes']['CodInterno'])->count() == 0) {
                $fuel_station = new FuelStation();
                $fuel_station->fill($information);
                $fuel_station->save();
                $num_success++;
            } else {
                $num_fails++;
            }
        }
        echo('Success: '.$num_success.'; Fails: '.$num_fails);
    }
}
