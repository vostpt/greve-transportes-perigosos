<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Controllers\CacheController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateStationsCacheJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 60;

    public function handle(CacheController $cache): void
    {
        $cache->updateStations();
    }

    public function uniqueId(): string
    {
        return 'stations-cache';
    }
}
