<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entry;
use App\FuelStation;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntriesController extends Controller
{
    public function list()
    {
        return response()->view('entries/list');
    }

    public function add(Request $request)
    {
        return response()->json(['success' => 0]);
        $validatedData = $request->validate([
            'fuel_station' => 'required|exists:fuel_stations,id',
            'gasoline'     => 'required|boolean',
            'diesel'       => 'required|boolean',
            'lpg'          => 'required|boolean',
            'captcha'      => 'required|string',
        ]);
        try {
            $url  = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret'   => env('GOOGLE_RECAPTCHA_V3_SECRET'),
                'response' => $validatedData['captcha'],
            ];
            $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => \http_build_query($data),
                ],
            ];
            $context    = \stream_context_create($options);
            $resultJSON = \file_get_contents($url, false, $context);
            $result     = \json_decode($resultJSON);
            if ($result->success != true) {
                return response()->json(['success' => 0]);
            }
            $station = FuelStation::findOrFail($validatedData['fuel_station']);
            if ($station->brand == 'Prio' || $station->brand == 'OZ Energia' || $station->brand == 'Ecobrent' || $station->brand == 'Bxpress' || $station->brand == 'Tfuel') {
                return response()->json(['success' => 0]);
            }
            $data = [
                'has_gasoline' => $validatedData['gasoline'],
                'has_diesel'   => $validatedData['diesel'],
                'has_lpg'      => $validatedData['lpg'],
            ];
            if ($user = Auth::user()) {
                $station->fill($data);
                $station->save();
                $cacheController = new CacheController;
                $cacheController->updateStations();
                return response()->json(['success' => 1]);
            } else {
                $entry                = new Entry();
                $data['ip']           = \Request::ip();
                $data['fuel_station'] = $validatedData['fuel_station'];
                $entry->fill($data);
                $entry->save();
                return response()->json(['success' => 1]);
            }
        } catch (Exception $e) {
            return response()->json(['success' => 0]);
        }
    }

    public function push(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:entries',
        ]);
        try {
            $entry = Entry::findOrFail($validatedData['id']);
            $entry->push();
            return redirect('/panel/entries/list')->with('status', 'Entrada Validada Manualmente!');
        } catch (Exception $e) {
            return redirect('/panel/entries/list')->with('status', 'Erro ao validar entrada!');
        }
    }

    public function fetch_pending()
    {
        $entries_final = [];
        $entries       = DB::table('entries')->select(DB::raw('MAX(id) as id,COUNT(DISTINCT(ip)) as total, fuel_station, has_gasoline, has_diesel, has_lpg'))->where('used', '=', 0)->where('created_at', '>=', \Carbon\Carbon::now()->subHour())->groupBy('fuel_station', 'has_gasoline', 'has_diesel', 'has_lpg')->get();
        foreach ($entries as $entry) {
            $fuel_station_string = FuelStation::find($entry->fuel_station)->string;
            $entries_final[]     = [
                'id'           => $entry->id,
                'fuel_station' => $fuel_station_string,
                'has_gasoline' => $entry->has_gasoline,
                'has_diesel'   => $entry->has_diesel,
                'has_lpg'      => $entry->has_lpg,
                'count'        => $entry->total,
            ];
        }
        return response()->json(['data' => $entries_final]);
    }
}
