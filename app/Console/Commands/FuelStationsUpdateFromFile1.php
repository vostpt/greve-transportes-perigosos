<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\FuelStation;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FuelStationsUpdateFromFile1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:updateFromFile:1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse Fuel Stations from Local Files (Source #1)';

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
        $xml_string           = Storage::disk('local')->get('data/postos-1.xml');
        $xml                  = \simplexml_load_string($xml_string);
        $json                 = \json_encode($xml);
        $object               = \json_decode($json, true);
        $num_of_fuel_stations = \count($object['Worksheet']['Table']['Row']);
        $num_success          = 0;
        $num_fails            = 0;
        for ($i = 1; $i < $num_of_fuel_stations; $i++) {
            $row = $object['Worksheet']['Table']['Row'][$i]['Cell'];
            if (FuelStation::where('id_station', '=', $row[1]['Data'])->count() == 0) {
                $num_fails++;
            } else {
                $fuel_station        = FuelStation::where('id_station', '=', $row[1]['Data'])->first();
                $fuel_station->name  = $row[2]['Data'];
                $fuel_station->usage = $row[4]['Data'];
                $fuel_station->type  = $row[5]['Data'];
                $fuel_station->save();
                $num_success++;
            }
        }
        echo('Success: '.$num_success.'; Fails: '.$num_fails);
    }
}
