<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Controllers\FuelStationsController;
use Illuminate\Console\Command;

class FuelStationsUpdateStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stations:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Force cache update of stats';

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
        $controller->updateStats();
    }
}
