<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\FuelStation;
use Illuminate\Support\Facades\Storage;

class FuelStationsController extends Controller
{
    //
    public function updateCache()
    {
        $json = FuelStation::all('name', 'brand', 'usage', 'district', 'county', 'address', 'long', 'lat', 'repa', 'sell_gasoline', 'sell_diesel', 'sell_lpg', 'has_gasoline', 'has_diesel', 'has_lpg')->toJson();
        Storage::disk('public')->put('data/cache.json', $json);
    }
}
