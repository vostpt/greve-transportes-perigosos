<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SubmitEntryRequest;
use App\Jobs\UpdateStationsCacheJob;
use App\Models\Entry;
use App\Models\FuelStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EntriesController extends Controller
{
    public function list(): Response
    {
        return response()->view('entries.list');
    }

    public function add(SubmitEntryRequest $request): JsonResponse
    {
        $data = $request->validated();

        $station = FuelStation::findOrFail($data['fuel_station']);

        if (in_array($station->brand, config('fuel_stations.submission_blacklist'), true)) {
            return response()->json(['success' => 0, 'reason' => 'brand_opted_out']);
        }

        $payload = [
            'has_gasoline' => (bool) $data['gasoline'],
            'has_diesel' => (bool) $data['diesel'],
            'has_lpg' => (bool) $data['lpg'],
        ];

        if (Auth::check()) {
            $station->update($payload);
            UpdateStationsCacheJob::dispatch();

            return response()->json(['success' => 1]);
        }

        Entry::create($payload + [
            'fuel_station' => $station->id,
            'ip' => $request->ip(),
        ]);

        return response()->json(['success' => 1]);
    }

    public function push(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id' => 'required|exists:entries,id',
        ]);

        Entry::findOrFail($validated['id'])->promote();
        UpdateStationsCacheJob::dispatch();

        return redirect('/panel/entries/list')->with('status', 'Entrada validada manualmente.');
    }

    public function fetchPending(): JsonResponse
    {
        $rows = DB::table('entries')
            ->selectRaw('MAX(id) as id, COUNT(DISTINCT(ip)) as total, fuel_station, has_gasoline, has_diesel, has_lpg')
            ->where('used', false)
            ->where('created_at', '>=', now()->subHour())
            ->groupBy('fuel_station', 'has_gasoline', 'has_diesel', 'has_lpg')
            ->get();

        $stations = FuelStation::query()
            ->whereIn('id', $rows->pluck('fuel_station'))
            ->get()
            ->keyBy('id');

        return response()->json([
            'data' => $rows->map(fn ($row) => [
                'id' => $row->id,
                'fuel_station' => $stations[$row->fuel_station]->string ?? "#{$row->fuel_station}",
                'has_gasoline' => (bool) $row->has_gasoline,
                'has_diesel' => (bool) $row->has_diesel,
                'has_lpg' => (bool) $row->has_lpg,
                'count' => $row->total,
            ]),
        ]);
    }
}
