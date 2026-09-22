<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\UpdateStationsCacheJob;
use App\Models\FuelStation;
use App\Models\Option;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FuelStationsController extends Controller
{
    public function list(): Response
    {
        if ((int) (Option::get('stress_lockdown') ?? '0') !== 0) {
            return response('Feature disabled while stress lockdown is active.', 503)
                ->header('Content-Type', 'text/plain');
        }

        return response()->view('stations.list');
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:fuel_stations,id',
            'sell_gasoline' => 'required|boolean',
            'sell_diesel' => 'required|boolean',
            'sell_lpg' => 'required|boolean',
            'repa' => 'required|boolean',
        ]);

        $station = FuelStation::findOrFail($data['id']);
        unset($data['id']);
        $station->update($data);
        UpdateStationsCacheJob::dispatch();

        return redirect('panel/stations/list')->with('status', 'Estação atualizada!');
    }

    public function updateAvailable(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => 'required|exists:fuel_stations,id',
            'has_gasoline' => 'required|boolean',
            'has_diesel' => 'required|boolean',
            'has_lpg' => 'required|boolean',
        ]);

        $station = FuelStation::findOrFail($data['id']);
        unset($data['id']);
        $station->update($data);
        UpdateStationsCacheJob::dispatch();

        return response()->json(['success' => true]);
    }

    public function fetchAll(): JsonResponse
    {
        return response()->json([
            'data' => FuelStation::query()
                ->select(['id', 'name', 'brand', 'sell_gasoline', 'sell_diesel', 'sell_lpg',
                    'repa', 'lat', 'long', 'has_gasoline', 'has_diesel', 'has_lpg'])
                ->get(),
        ]);
    }
}
