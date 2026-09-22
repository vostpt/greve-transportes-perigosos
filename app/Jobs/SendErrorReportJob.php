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

class SendErrorReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public array $backoff = [30, 120, 600];

    public function __construct(public array $payload) {}

    public function handle(): void
    {
        $webhook = config('services.errors_webhook.url');

        if (! $webhook) {
            Log::info('errors_webhook not configured, dropping report', $this->payload);

            return;
        }

        $response = Http::timeout(10)->get($webhook, $this->payload);

        if (! $response->successful()) {
            Log::warning('errors_webhook rejected report', [
                'status' => $response->status(),
                'payload' => $this->payload,
            ]);
            $this->release(60);
        }
    }
}
