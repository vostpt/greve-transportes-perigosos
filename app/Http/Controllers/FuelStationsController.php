<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\FuelStation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class FuelStationsController extends Controller
{
    //
    public function updateCache()
    {
        $json = FuelStation::all('name', 'brand', 'usage', 'district', 'county', 'address', 'long', 'lat', 'repa', 'sell_gasoline', 'sell_diesel', 'sell_lpg', 'has_gasoline', 'has_diesel', 'has_lpg')->toJson();
        Storage::disk('public')->put('data/cache.json', $json);
        if (env('CLOUDFLARE_API_ENABLE', false) == 'true') {
            $path_to_clear = URL::to('/storage/data/cache.json');
            $auth_email    = env('CLOUDFLARE_API_EMAIL');
            $auth_key      = env('CLOUDFLARE_API_KEY');
            $api_endpoint  = 'https://api.cloudflare.com/client/v4/zones/'.env('CLOUDFLARE_API_IDENTIFIER').'/purge_cache';
            $headers       = [
            'X-Auth-Email' => $auth_email,
            'X-Auth-Key'   => $auth_key,
            'content-type' => 'application/json',
            ];
            $data = [
                'files' => [$path_to_clear],
            ];
            $client = new \GuzzleHttp\Client();
            $client->request(
                'POST',
                $api_endpoint,
                [
                'headers' => $headers,
                'json'    => $data,
            ]
            );
        }
    }

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
            'id'             => 'required|exists:entries',
            'name'           => 'required',
            'brand'          => 'required',
            'sells_gasoline' => 'required',
            'sells_diesel'   => 'required',
            'sells_lpg'      => 'required',
            'repa'           => 'required',
        ]);
        try {
            $fuel_station = FuelStation::findOrFail($validatedData['id']);
            unset($validatedData['id']);
            $fuel_station->update($validatedData);
            $this->updateCache();
            return redirect('stations/list')->with('status', 'Estação Atualizada!');
        } catch (Exception $e) {
            return redirect('stations/list')->with('status', 'Erro ao atualizar estação!');
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
            ];
        }
        return response()->json(['data' => $stations_final]);
    }
}
