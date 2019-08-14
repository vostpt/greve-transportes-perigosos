<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\ExternalAuth;
use App\FuelStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class APIController extends Controller
{
    public function home()
    {
        return response()->view('api/home');
    }

    public function info(Request $request)
    {
        if ($request->has('key') && $request->has('secret')) {
            $ext_auth = ExternalAuth::where([['key', '=', $request->input('key')],['secret', '=', $request->input('secret')]]);
            if ($ext_auth->count() > 0) {
                $ext_auth = $ext_auth->first();

                $info = ['brand' => $ext_auth->brand];
                return response()->json($info);
            }
        }
        return response()->json([]);
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
                        'id'            => $station->id,
                        'name'          => $station->name,
                        'brand'         => $station->brand,
                        'sell_gasoline' => $station->sell_gasoline,
                        'sell_diesel'   => $station->sell_diesel,
                        'sell_lpg'      => $station->sell_lpg,
                        'has_gasoline'  => $station->has_gasoline,
                        'has_diesel'    => $station->has_diesel,
                        'has_lpg'       => $station->has_lpg,
                        'lat'           => $station->long,
                        'long'          => $station->lat,
                        'repa'          => $station->repa,
                        'county'        => $station->county,
                        'district'      => $station->district,
                    ];
                }
                if ($ext_auth->brand == 'Tfuel') {
                    $stations = FuelStation::where('id', '=', 972)->orWhere('id', '=', 1549)->get();
                    foreach ($stations as $station) {
                        $output[] = [
                            'id'            => $station->id,
                            'name'          => $station->name,
                            'brand'         => $station->brand,
                            'sell_gasoline' => $station->sell_gasoline,
                            'sell_diesel'   => $station->sell_diesel,
                            'sell_lpg'      => $station->sell_lpg,
                            'has_gasoline'  => $station->has_gasoline,
                            'has_diesel'    => $station->has_diesel,
                            'has_lpg'       => $station->has_lpg,
                            'lat'           => $station->long,
                            'long'          => $station->lat,
                            'repa'          => $station->repa,
                            'county'        => $station->county,
                            'district'      => $station->district,
                        ];
                    }
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
                    if (($fuel_station->brand == $ext_auth->brand) || ($ext_auth->brand == 'WRITEREAD') || ($fuel_station->id == 972 && $ext_auth->brand == 'Tfuel') || ($fuel_station->id == 1549 && $ext_auth->brand == 'Tfuel')) {
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

    public function add(Request $request)
    {
        $output = ['success' => 0];
        if ($request->has('key') && $request->has('secret') && $request->has('name') && $request->has('brand') && $request->has('repa') && $request->has('sell_gasoline') && $request->has('sell_diesel') && $request->has('sell_lpg') && $request->has('county') && $request->has('district') && $request->has('lat') && $request->has('long')) {
            $ext_auth = ExternalAuth::where([['key', '=', $request->input('key')],['secret', '=', $request->input('secret')]]);
            if ($ext_auth->count() > 0) {
                $ext_auth = $ext_auth->first();
                if (($request->input('brand') == $ext_auth->brand) || ($ext_auth->brand == 'WRITEREAD')) {
                    $sell_gasoline = \intval($request->input('sell_gasoline'));
                    $sell_diesel   = \intval($request->input('sell_diesel'));
                    $sell_lpg      = \intval($request->input('sell_lpg'));
                    if (! \is_nan($sell_gasoline) && ! \is_nan($sell_diesel) && ! \is_nan($sell_lpg)) {
                        if ($sell_gasoline > 1) {
                            $sell_gasoline = 1;
                        } elseif ($sell_gasoline < 0) {
                            $sell_gasoline = 0;
                        }
                        if ($sell_diesel > 1) {
                            $sell_diesel = 1;
                        } elseif ($sell_diesel < 0) {
                            $sell_diesel = 0;
                        }
                        if ($sell_lpg > 1) {
                            $sell_lpg = 1;
                        } elseif ($sell_lpg < 0) {
                            $sell_lpg = 0;
                        }
                        $county = $request->input('county');
                        if ($county == null) {
                            $county = '';
                        }
                        $district = $request->input('district');
                        if ($district == null) {
                            $district = '';
                        }
                        $repa = $request->input('repa');
                        if ($repa == null) {
                            $repa = '';
                        }
                        $data = [
                                'repa'          => $repa,
                                'source_id'     => 'api',
                                'brand'         => $request->input('brand'),
                                'name'          => $request->input('name'),
                                'sell_gasoline' => $sell_gasoline,
                                'sell_diesel'   => $sell_diesel,
                                'sell_lpg'      => $sell_lpg,
                                'has_gasoline'  => $sell_gasoline,
                                'has_diesel'    => $sell_diesel,
                                'has_lpg'       => $sell_lpg,
                                'long'          => \floatval($request->input('lat')),
                                'lat'           => \floatval($request->input('long')),
                                'county'        => $county,
                                'district'      => $district,
                            ];
                        $fuel_station = new FuelStation();
                        $fuel_station->fill($data);
                        $fuel_station->save();
                        $cacheController = new CacheController();
                        $cacheController->updateStations();
                        $output = ['success' => 1];
                    }
                }
            }
        }
        return response()->json($output);
    }

    public function change(Request $request)
    {
        $output = ['success' => 0];
        if ($request->has('key') && $request->has('secret') && $request->has('name') && $request->has('brand') && $request->has('repa') && $request->has('sell_gasoline') && $request->has('sell_diesel') && $request->has('sell_lpg') && $request->has('id') && $request->has('county') && $request->has('district') && $request->has('lat') && $request->has('long')) {
            $ext_auth = ExternalAuth::where([['key', '=', $request->input('key')],['secret', '=', $request->input('secret')]]);
            if ($ext_auth->count() > 0) {
                $ext_auth     = $ext_auth->first();
                $fuel_station = FuelStation::where('id', '=', $request->input('id'));
                if ($fuel_station->count() > 0) {
                    $fuel_station = $fuel_station->first();
                    if (($fuel_station->brand == $ext_auth->brand) || ($ext_auth->brand == 'WRITEREAD') || ($fuel_station->id == 972 && $ext_auth->brand == 'Tfuel') || ($fuel_station->id == 1549 && $ext_auth->brand == 'Tfuel')) {
                        $sell_gasoline = \intval($request->input('sell_gasoline'));
                        $sell_diesel   = \intval($request->input('sell_diesel'));
                        $sell_lpg      = \intval($request->input('sell_lpg'));
                        if (! \is_nan($sell_gasoline) && ! \is_nan($sell_diesel) && ! \is_nan($sell_lpg)) {
                            if ($sell_gasoline > 1) {
                                $sell_gasoline = 1;
                            } elseif ($sell_gasoline < 0) {
                                $sell_gasoline = 0;
                            }
                            if ($sell_diesel > 1) {
                                $sell_diesel = 1;
                            } elseif ($sell_diesel < 0) {
                                $sell_diesel = 0;
                            }
                            if ($sell_lpg > 1) {
                                $sell_lpg = 1;
                            } elseif ($sell_lpg < 0) {
                                $sell_lpg = 0;
                            }
                            $county = $request->input('county');
                            if ($county == null) {
                                $county = '';
                            }
                            $district = $request->input('district');
                            if ($district == null) {
                                $district = '';
                            }
                            $repa = $request->input('repa');
                            if ($repa == null) {
                                $repa = '';
                            }
                            $data = [
                                'repa'          => $repa,
                                'source_id'     => 'api',
                                'brand'         => $request->input('brand'),
                                'name'          => $request->input('name'),
                                'sell_gasoline' => $sell_gasoline,
                                'sell_diesel'   => $sell_diesel,
                                'sell_lpg'      => $sell_lpg,
                                'has_gasoline'  => $sell_gasoline,
                                'has_diesel'    => $sell_diesel,
                                'has_lpg'       => $sell_lpg,
                                'long'          => \floatval($request->input('lat')),
                                'lat'           => \floatval($request->input('long')),
                                'county'        => $county,
                                'district'      => $district,
                            ];
                            $fuel_station->fill($data);
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

    public function fetch_csv()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=file.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'name',
            'brand',
            'lat',
            'long',
            'repa',
            'sell_gasoline',
            'sell_diesel',
            'sell_lpg',
            'has_gasoline',
            'has_diesel',
            'has_lpg',
            'district',
            'county',
        ];

        $fuel_stations = FuelStation::all($columns);

        $cb = function () use ($fuel_stations, $columns) {
            $file = \fopen('php://output', 'w');
            \fprintf($file, \chr(0xEF).\chr(0xBB).\chr(0xBF));
            \fputcsv($file, $columns);

            foreach ($fuel_stations as $fuel_station) {
                if ($fuel_station['brand'] != 'POSTO ES') {
                    $long_save            = $fuel_station['long'];
                    $fuel_station['long'] = $fuel_station['lat'];
                    $fuel_station['lat']  = $long_save;
                    \fputcsv($file, $fuel_station->toArray());
                }
            }
            \fclose($file);
        };

        return response()->stream($cb, 200, $headers);
    }

    public function fetch_api_csv()
    {
        $password = Input::get('password');
        if ($password != env('API_V1_DOWNLOAD_API_CSV')) {
            return response('0', 200)->header('Content-Type', 'text/plain');
        }
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=file.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $columns = [
            'name',
            'brand',
            'lat',
            'long',
            'repa',
            'sell_gasoline',
            'sell_diesel',
            'sell_lpg',
            'has_gasoline',
            'has_diesel',
            'has_lpg',
            'district',
            'county',
            'source_id',
        ];

        $fuel_stations = FuelStation::all($columns)->where('source_id', '=', 'api');
        $cb            = function () use ($fuel_stations, $columns) {
            $file = \fopen('php://output', 'w');
            \fprintf($file, \chr(0xEF).\chr(0xBB).\chr(0xBF));
            \fputcsv($file, $columns);

            foreach ($fuel_stations as $fuel_station) {
                if ($fuel_station['brand'] != 'POSTO ES') {
                    $long_save            = $fuel_station['long'];
                    $fuel_station['long'] = $fuel_station['lat'];
                    $fuel_station['lat']  = $long_save;
                    \fputcsv($file, $fuel_station->toArray());
                }
            }
            \fclose($file);
        };

        return response()->stream($cb, 200, $headers);
    }
}
