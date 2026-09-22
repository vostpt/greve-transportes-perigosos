<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PurgeCloudflareUrlJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [10, 30, 120];

    public function __construct(public string $url) {}

    public function handle(): void
    {
        $token = config('services.cloudflare.token');
        $zone = config('services.cloudflare.zone');

        if (! $token || ! $zone) {
            return;
        }

        $response = Http::withToken($token)
            ->timeout(10)
            ->post("https://api.cloudflare.com/client/v4/zones/{$zone}/purge_cache", [
                'files' => [$this->url],
            ]);

        if (! $response->successful()) {
            Log::warning('Cloudflare purge failed', [
                'url' => $this->url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $this->release(30);
        }
    }
}
