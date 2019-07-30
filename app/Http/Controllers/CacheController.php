<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entry;
use App\FuelStation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CacheController extends Controller
{
    public function clearCloudflare($url_to_clear)
    {
        if (env('CLOUDFLARE_API_ENABLE', false) == 'true') {
            $path_to_clear = $url_to_clear;
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

    public function updateStats()
    {
        $data = [
            'entries_last_hour'    => 0,
            'entries_last_day'     => 0,
            'entries_total'        => 0,
            'stations_none'        => 0,
            'stations_no_gasoline' => 0,
            'stations_no_diesel'   => 0,
            'stations_no_lpg'      => 0,
        ];
        $data['entries_last_hour']    = Entry::lastHour()->count();
        $data['entries_last_day']     = Entry::lastDay()->count();
        $data['entries_total']        = Entry::all()->count();
        $data['stations_none']        = FuelStation::empty()->count();
        $data['stations_no_gasoline'] = FuelStation::noGasoline()->count();
        $data['stations_no_diesel']   = FuelStation::noDiesel()->count();
        $data['stations_no_lpg']      = FuelStation::noLPG()->count();
        Storage::disk('public')->put('data/stats.json', \json_encode($data));
        $this->clearCloudflare(URL::to('/storage/data/stats.json'));
    }

    public function updateStations()
    {
        $json = FuelStation::all('id', 'name', 'brand', 'long', 'lat', 'repa', 'sell_gasoline', 'sell_diesel', 'sell_lpg', 'has_gasoline', 'has_diesel', 'has_lpg')->toJson();
        Storage::disk('public')->put('data/cache.json', $json);
        $this->clearCloudflare(URL::to('/storage/data/cache.json'));
    }
}
