<?php

namespace App\Console\Commands;

use App\FuelStation;
use Illuminate\Console\Command;

class UpdateRepasFromUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fuelstations:update-repas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update repas from google spreadsheet';

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

        foreach ($fetched_object as $obj) {
           if(!empty($obj[0])){
               $s = FuelStation::where('name', $obj[5])->get();

               if(isset($s[0])){
                   $s = $s[0];

                   $data = array(
                       'repa' => $obj[0]
                   );

                   $s->fill($data);
                   $s->save();

               }
           }
        }
    }
}
