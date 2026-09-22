<?php

declare(strict_types=1);

use App\Jobs\UpdateStatsJob;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api',
        then: function (): void {
            RateLimiter::for('login', function (Request $request) {
                return [
                    Limit::perMinute(5)->by($request->input('email').'|'.$request->ip()),
                    Limit::perMinute(20)->by($request->ip()),
                ];
            });
        },
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->job(new UpdateStatsJob)->everyMinute()->onOneServer()->name('stats-refresh');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // Trusted proxies default to '*' for local Docker (nginx forwards the request).
        // Production MUST override TRUSTED_PROXIES with the Cloudflare/CIDR ranges
        // that actually front this app — never leave '*' exposed to the internet.
        // env() here runs at bootstrap time before config is compiled, which is
        // why we call env() and not config() — the value is only read once when
        // the middleware is registered.
        $middleware->trustProxies(
            at: env('TRUSTED_PROXIES', '*'), // @phpstan-ignore-line larastan.noEnvCallsOutsideOfConfig
            headers: SymfonyRequest::HEADER_X_FORWARDED_FOR
                | SymfonyRequest::HEADER_X_FORWARDED_HOST
                | SymfonyRequest::HEADER_X_FORWARDED_PORT
                | SymfonyRequest::HEADER_X_FORWARDED_PROTO
                | SymfonyRequest::HEADER_X_FORWARDED_AWS_ELB
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
