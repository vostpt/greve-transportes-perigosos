<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Laravel\Sentinel\Drivers\Driver;
use Laravel\Sentinel\Sentinel;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Sentinel::extend('horizon', fn () => new class(fn () => $this->app) extends Driver
        {
            public function authorize(Request $request): bool
            {
                if ($this->app()->environment('local')) {
                    return true;
                }

                $user = $request->user();

                return $user !== null && $user->email_verified_at !== null;
            }
        });
    }
}
