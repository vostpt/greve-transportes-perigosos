<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\ExternalAuth;
use App\FuelStation;
use Illuminate\Http\Request;

class APIController extends Controller
{
    public function home()
    {
        return response()->view('api/home');
    }

    public function fetch(Request $request)
    {
        if ($request->has('key') && $request->has('secret')) {
            $ext_auth = ExternalAuth::where([['key', '=', $request->input('key')],['secret', '=', $request->input('secret')]]);
            if ($ext_auth->count() > 0) {
                $ext_auth = $ext_auth->first();
                $stations = [];
                if ($ext_auth->brand == 'READONLY' || $ext_auth->brand == 'WRITEREAD') {
                    $stations = FuelStation::all();
                } else {
                    $stations = FuelStation::where('brand', '=', $ext_auth->brand)->get();
                }
                $output = [];
                foreach ($stations as $station) {
                    $output[] = [
                        'id'           => $station->id,
                        'name'         => $station->name,
                        'has_gasoline' => $station->has_gasoline,
                        'has_diesel'   => $station->has_diesel,
                        'has_lpg'      => $station->has_lpg,
                        'lat'          => $station->lat,
                        'long'         => $station->long,
                    ];
                }

                return response()->json($output);
            }
        }
        return response()->json([]);
    }

    public function push(Request $request)
    {
        $output = ['success' => 0];
        if ($request->has('key') && $request->has('secret') && $request->has('has_gasoline') && $request->has('has_diesel') && $request->has('has_lpg') && $request->has('id')) {
            $ext_auth = ExternalAuth::where([['key', '=', $request->input('key')],['secret', '=', $request->input('secret')]]);
            if ($ext_auth->count() > 0) {
                $ext_auth     = $ext_auth->first();
                $fuel_station = FuelStation::where('id', '=', $request->input('id'));
                if ($fuel_station->count() > 0) {
                    $fuel_station = $fuel_station->first();
                    if (($fuel_station->brand == $ext_auth->brand) || ($ext_auth->brand == 'WRITEREAD')) {
                        $has_gasoline = \intval($request->input('has_gasoline'));
                        $has_diesel   = \intval($request->input('has_diesel'));
                        $has_lpg      = \intval($request->input('has_lpg'));
                        if (! \is_nan($has_gasoline) && ! \is_nan($has_diesel) && ! \is_nan($has_lpg)) {
                            if ($has_gasoline > 1) {
                                $has_gasoline = 1;
                            } elseif ($has_gasoline < 0) {
                                $has_gasoline = 0;
                            }
                            if ($has_diesel > 1) {
                                $has_diesel = 1;
                            } elseif ($has_diesel < 0) {
                                $has_diesel = 0;
                            }
                            if ($has_lpg > 1) {
                                $has_lpg = 1;
                            } elseif ($has_lpg < 0) {
                                $has_lpg = 0;
                            }
                            $fuel_station->update([
                                'has_gasoline' => $has_gasoline,
                                'has_diesel'   => $has_diesel,
                                'has_lpg'      => $has_lpg,
                            ]);
                            $fuel_station->save();
                            $cacheController = new CacheController();
                            $cacheController->updateStations();
                            $output = ['success' => 1];
                        }
                    }
                }
            }
        }
        return response()->json($output);
    }
}
