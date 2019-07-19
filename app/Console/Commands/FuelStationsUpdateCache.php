<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Controllers\FuelStationsController;
use Illuminate\Console\Command;

class FuelStationsUpdateCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force cache update current fuel stations';

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
        $controller = new FuelStationsController();
        $controller->updateCache();
    }
}
