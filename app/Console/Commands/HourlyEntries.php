<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Controllers\CacheController;
use Illuminate\Console\Command;

class HourlyEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entries:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate hourly entry stats file';

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
        $cacheController = new CacheController();
        $cacheController->updateStatsBeginnig();
    }
}
