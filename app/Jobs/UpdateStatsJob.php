<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Http\Controllers\CacheController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;

class UpdateStatsJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $uniqueFor = 300;

    public int $timeout = 300;

    public function handle(CacheController $cache): void
    {
        $cache->updateStats();
        $cache->updateEntriesLast12Hours();
    }

    public function uniqueId(): string
    {
        return 'stats-refresh';
    }

    public function middleware(): array
    {
        return [(new WithoutOverlapping('stats-refresh'))->expireAfter(600)];
    }
}
