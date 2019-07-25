<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entry;
use App\FuelStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntriesController extends Controller
{
    public function list()
    {
        return response()->view('entries/list');
    }

    public function push(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:entries',
        ]);
        try {
            $entry = Entry::findOrFail($validatedData['id']);
            $entry->push();
            return redirect('entries/list')->with('status', 'Entrada Validada Manualmente!');
        } catch (Exception $e) {
            return redirect('entries/list')->with('status', 'Erro ao validar entrada!');
        }
    }

    public function fetch_pending()
    {
        $entries_final = [];
        $entries       = DB::table('entries')->select(DB::raw('MAX(id) as id,count(*) as total, fuel_station, has_gasoline, has_diesel, has_lpg'))->where('used', '=', 0)->groupBy('fuel_station', 'has_gasoline', 'has_diesel', 'has_lpg')->get();
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
