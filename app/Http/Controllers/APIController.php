<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\UpdateStationsCacheJob;
use App\Models\ExternalAuth;
use App\Models\FuelStation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class APIController extends Controller
{
    public function home(): Response
    {
        return response()->view('api/home');
    }

    public function info(Request $request): JsonResponse
    {
        $auth = ExternalAuth::authenticate($request->input('key'), $request->input('secret'));
        if (! $auth) {
            return response()->json([]);
        }

        return response()->json(['brand' => $auth->brand]);
    }

    public function fetch(Request $request): JsonResponse
    {
        $auth = ExternalAuth::authenticate($request->input('key'), $request->input('secret'));
        if (! $auth) {
            return response()->json([]);
        }

        $stations = $auth->canReadAll()
            ? FuelStation::all()
            : FuelStation::query()
                ->where('brand', $auth->brand)
                ->orWhere('brand_management', $auth->brand)
                ->get();

        return response()->json($stations->map(fn (FuelStation $station) => [
            'id' => $station->id,
            'name' => $station->name,
            'brand' => $station->brand,
            'sell_gasoline' => $station->sell_gasoline,
            'sell_diesel' => $station->sell_diesel,
            'sell_lpg' => $station->sell_lpg,
            'has_gasoline' => $station->has_gasoline,
            'has_diesel' => $station->has_diesel,
            'has_lpg' => $station->has_lpg,
            'lat' => $station->lat,
            'long' => $station->long,
            'repa' => $station->repa,
            'county' => $station->county,
            'district' => $station->district,
        ])->all());
    }

    public function push(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => 'required|string',
            'secret' => 'required|string',
            'id' => 'required|integer|exists:fuel_stations,id',
            'has_gasoline' => 'required|boolean',
            'has_diesel' => 'required|boolean',
            'has_lpg' => 'required|boolean',
        ]);

        $auth = ExternalAuth::authenticate($data['key'], $data['secret']);
        if (! $auth) {
            return response()->json(['success' => 0]);
        }

        $station = FuelStation::findOrFail($data['id']);
        if (! $auth->ownsStation($station)) {
            return response()->json(['success' => 0]);
        }

        $station->update([
            'has_gasoline' => (bool) $data['has_gasoline'],
            'has_diesel' => (bool) $data['has_diesel'],
            'has_lpg' => (bool) $data['has_lpg'],
        ]);

        UpdateStationsCacheJob::dispatch();

        return response()->json(['success' => 1]);
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => 'required|string',
            'secret' => 'required|string',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'repa' => 'required',
            'sell_gasoline' => 'required|boolean',
            'sell_diesel' => 'required|boolean',
            'sell_lpg' => 'required|boolean',
            'county' => 'nullable|string',
            'district' => 'nullable|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $auth = ExternalAuth::authenticate($data['key'], $data['secret']);
        if (! $auth) {
            return response()->json(['success' => 0]);
        }

        $management = $data['brand'] !== $auth->brand && ! $auth->canWriteAny()
            ? $auth->brand
            : null;

        FuelStation::create([
            'name' => $data['name'],
            'brand' => $data['brand'],
            'brand_management' => $management,
            'repa' => (bool) $data['repa'],
            'source_id' => 'api',
            'sell_gasoline' => (bool) $data['sell_gasoline'],
            'sell_diesel' => (bool) $data['sell_diesel'],
            'sell_lpg' => (bool) $data['sell_lpg'],
            'has_gasoline' => (bool) $data['sell_gasoline'],
            'has_diesel' => (bool) $data['sell_diesel'],
            'has_lpg' => (bool) $data['sell_lpg'],
            'lat' => (float) $data['lat'],
            'long' => (float) $data['long'],
            'county' => $data['county'] ?? '',
            'district' => $data['district'] ?? '',
        ]);

        UpdateStationsCacheJob::dispatch();

        return response()->json(['success' => 1]);
    }

    public function change(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => 'required|string',
            'secret' => 'required|string',
            'id' => 'required|integer|exists:fuel_stations,id',
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'repa' => 'required',
            'sell_gasoline' => 'required|boolean',
            'sell_diesel' => 'required|boolean',
            'sell_lpg' => 'required|boolean',
            'county' => 'nullable|string',
            'district' => 'nullable|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        $auth = ExternalAuth::authenticate($data['key'], $data['secret']);
        if (! $auth) {
            return response()->json(['success' => 0]);
        }

        $station = FuelStation::findOrFail($data['id']);
        if (! $auth->ownsStation($station)) {
            return response()->json(['success' => 0]);
        }

        $management = $data['brand'] !== $auth->brand && ! $auth->canWriteAny()
            ? $auth->brand
            : null;

        $station->update([
            'name' => $data['name'],
            'brand' => $data['brand'],
            'brand_management' => $management,
            'repa' => (bool) $data['repa'],
            'source_id' => 'api',
            'sell_gasoline' => (bool) $data['sell_gasoline'],
            'sell_diesel' => (bool) $data['sell_diesel'],
            'sell_lpg' => (bool) $data['sell_lpg'],
            'has_gasoline' => (bool) $data['sell_gasoline'],
            'has_diesel' => (bool) $data['sell_diesel'],
            'has_lpg' => (bool) $data['sell_lpg'],
            'lat' => (float) $data['lat'],
            'long' => (float) $data['long'],
            'county' => $data['county'] ?? '',
            'district' => $data['district'] ?? '',
        ]);

        UpdateStationsCacheJob::dispatch();

        return response()->json(['success' => 1]);
    }

    public function fetchCsv(): StreamedResponse
    {
        return $this->streamCsv(
            FuelStation::query()->where('brand', '!=', 'POSTO ES')->get(),
            [
                'id', 'name', 'brand', 'lat', 'long', 'repa',
                'sell_gasoline', 'sell_diesel', 'sell_lpg',
                'has_gasoline', 'has_diesel', 'has_lpg',
                'district', 'county', 'ense_id', 'ense_gasoline', 'ense_diesel',
            ]
        );
    }

    public function fetchApiCsv(Request $request): StreamedResponse|Response
    {
        if ($request->input('password') !== config('services.api_v1.csv_password')) {
            return response('0', 200)->header('Content-Type', 'text/plain');
        }

        return $this->streamCsv(
            FuelStation::query()
                ->where('brand', '!=', 'POSTO ES')
                ->where('source_id', 'api')
                ->get(),
            [
                'id', 'name', 'brand', 'lat', 'long', 'repa',
                'sell_gasoline', 'sell_diesel', 'sell_lpg',
                'has_gasoline', 'has_diesel', 'has_lpg',
                'district', 'county', 'source_id',
            ]
        );
    }

    private function streamCsv($stations, array $columns): StreamedResponse
    {
        return response()->stream(function () use ($stations, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);
            foreach ($stations as $station) {
                fputcsv($file, array_map(fn ($col) => $station[$col], $columns));
            }
            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=fuel_stations.csv',
            'Cache-Control' => 'no-store',
        ]);
    }
}
