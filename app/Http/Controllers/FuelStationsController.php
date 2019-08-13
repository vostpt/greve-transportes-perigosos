<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\FuelStation;
use App\Option;
use Illuminate\Http\Request;

class FuelStationsController extends Controller
{
    //

    public function list()
    {
        if (Option::find('stress_lockdown')->value == 0) {
            return response()->view('stations/list');
        } else {
            return response('Feature Disabled due to Stress Lockdown (Disable at Options)', 200)->header('Content-Type', 'text/plain');
        }
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id'             => 'required|exists:fuel_stations',
            'sells_gasoline' => 'required',
            'sells_diesel'   => 'required',
            'sells_lpg'      => 'required',
            'repa'           => 'required',
        ]);
        try {
            $fuel_station = FuelStation::findOrFail($validatedData['id']);
            unset($validatedData['id']);
            $fuel_station->update($validatedData);
            $cacheController = new CacheController();
            $cacheController->updateStations();
            return redirect('panel/stations/list')->with('status', 'Estação Atualizada!');
        } catch (Exception $e) {
            return redirect('panel/stations/list')->with('status', 'Erro ao atualizar estação!');
        }
    }

    public function updateAvailable(Request $request)
    {
        $validatedData = $request->validate([
            'id'             => 'required|exists:fuel_stations',
            'has_gasoline' => 'boolean',
            'has_diesel'   => 'boolean',
            'has_lpg'      => 'boolean',
        ]);
        try {
            $fuel_station = FuelStation::findOrFail($validatedData['id']);
            unset($validatedData['id']);
            $fuel_station->update($validatedData);
            $cacheController = new CacheController();
            $cacheController->updateStations();
            return response()->json(['sucess' => true]);
        } catch (Exception $e) {
            return response()->json(['sucess' => false]);
        }
    }

    public function fetch_all()
    {
        $stations_final = [];
        $stations       = FuelStation::all();
        foreach ($stations as $station) {
            $stations_final[] = [
                'id'            => $station->id,
                'name'          => $station->name,
                'brand'         => $station->brand,
                'sell_gasoline' => $station->sell_gasoline,
                'sell_diesel'   => $station->sell_diesel,
                'sell_lpg'      => $station->sell_lpg,
                'repa'          => $station->repa,
                'lat'           => $station->lat,
                'long'          => $station->long,
                'has_gasoline'  => $station->has_gasoline,
                'has_diesel'    => $station->has_diesel,
                'has_lpg'       => $station->has_lpg
            ];
        }
        return response()->json(['data' => $stations_final]);
    }
}
