<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\PurgeCloudflareUrlJob;
use App\Models\Entry;
use App\Models\FuelStation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CacheController extends Controller
{
    private function disk(): string
    {
        return (string) config('fuel_stations.snapshot_disk', 'public');
    }

    private function put(string $path, array $payload): void
    {
        Storage::disk($this->disk())->put($path, json_encode($payload, JSON_UNESCAPED_UNICODE));
        PurgeCloudflareUrlJob::dispatch(URL::to('/storage/'.$path));
    }

    private function stationCounts(?string $district = null, ?string $county = null, ?string $brand = null): array
    {
        $filter = static function ($query) use ($district, $county, $brand) {
            if ($district !== null) {
                $query->where('district', $district);
            }
            if ($county !== null) {
                $query->where('county', $county);
            }
            if ($brand !== null) {
                $query->where('brand', $brand);
            }
        };

        return [
            'stations_total' => FuelStation::query()->tap($filter)->count(),
            'stations_none' => FuelStation::query()->tap($filter)->empty()->count(),
            'stations_partial' => FuelStation::query()->tap($filter)->partial()->count(),
            'stations_all' => FuelStation::query()->tap($filter)->withAll()->count(),
            'stations_no_gasoline' => FuelStation::query()->tap($filter)->noGasoline()->count(),
            'stations_no_diesel' => FuelStation::query()->tap($filter)->noDiesel()->count(),
            'stations_no_lpg' => FuelStation::query()->tap($filter)->noLPG()->count(),
            'stations_sell_gasoline' => FuelStation::query()->tap($filter)->sellGasoline()->count(),
            'stations_sell_diesel' => FuelStation::query()->tap($filter)->sellDiesel()->count(),
            'stations_sell_lpg' => FuelStation::query()->tap($filter)->sellLPG()->count(),
        ];
    }

    public function updateStations(): void
    {
        $payload = FuelStation::query()
            ->select(['id', 'name', 'brand', 'brand_management', 'lat', 'long', 'repa',
                'sell_gasoline', 'sell_diesel', 'sell_lpg', 'has_gasoline', 'has_diesel',
                'has_lpg', 'updated_at'])
            ->get()
            ->toArray();

        $this->put('data/cache.json', $payload);
    }

    public function updateStats(): void
    {
        $this->put('data/stats_entries.json', [
            'entries_last_hour' => Entry::query()->lastHour()->count(),
            'entries_last_day' => Entry::query()->lastDay()->count(),
            'entries_total' => Entry::query()->count(),
        ]);

        $this->put('data/stats_global.json', $this->stationCounts());

        $places = [];
        foreach (FuelStation::districts() as $district) {
            $this->put('data/stats_'.ucfirst(mb_strtolower($district)).'.json', $this->stationCounts($district));

            $counties = FuelStation::counties($district);
            $places[ucfirst(mb_strtolower($district))] = $counties->all();

            foreach ($counties as $county) {
                $this->put('data/stats_'.ucfirst(mb_strtolower($district)).'_'.$county.'.json', $this->stationCounts($district, $county));
            }
        }

        $this->put('data/places.json', $places);

        $brandStats = [];
        foreach (FuelStation::brands() as $brand) {
            $brandStats[$brand] = $this->stationCounts(brand: $brand);
        }
        $this->put('data/stats_brands.json', $brandStats);
    }

    public function updateHourlyEntries(): void
    {
        $first = Entry::query()->orderBy('created_at')->value('created_at');
        if (! $first) {
            $this->put('data/stats_entries_hourly.json', []);

            return;
        }

        $entries = [];
        $cursor = $first->copy();
        while ($cursor <= now()) {
            $next = $cursor->copy()->addHour();
            $entries[$cursor->toDateTimeString()] = Entry::query()
                ->whereBetween('created_at', [$cursor, $next])
                ->count();
            $cursor = $next;
        }

        $this->put('data/stats_entries_hourly.json', $entries);
    }

    public function updateEntriesLast12Hours(): void
    {
        $total = Entry::query()->count();
        $records = Entry::query()
            ->where('created_at', '>=', now()->subHours(12))
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->created_at)->format('H'));

        $data = ['total' => $total, 'records' => []];
        foreach ($records as $group) {
            $data['records'][] = [$group->first()->created_at->format('H'), $group->count()];
        }

        $this->put('data/stats_entries_last12.json', $data);
    }
}
