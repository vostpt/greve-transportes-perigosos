<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entry;
use App\FuelStation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class CacheController extends Controller
{
    private function clearCloudflare($url_to_clear)
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

    private function updateCounty($district, $county)
    {
        $county_data = [
            'stations_none'        => 0,
            'stations_partial'     => 0,
            'stations_all'         => 0,
            'stations_no_gasoline' => 0,
            'stations_no_diesel'   => 0,
            'stations_no_lpg'      => 0,
        ];
        $county_stations                     = FuelStation::where([['district','=',$district], ['county','=',$county]]);
        $county_data['stations_none']        = $county_stations->empty()->count();
        $county_data['stations_partial']     = $county_stations->partial()->count();
        $county_data['stations_all']         = $county_stations->withAll()->count();
        $county_data['stations_no_gasoline'] = $county_stations->noGasoline()->count();
        $county_data['stations_no_diesel']   = $county_stations->noDiesel()->count();
        $county_data['stations_no_lpg']      = $county_stations->noLPG()->count();
        Storage::disk('public')->put('data/stats_'.$district.'_'.$county.'.json', \json_encode($county_data));
        $this->clearCloudflare(URL::to('/storage/data/stats_'.$district.'_'.$county.'.json'));
    }

    private function updateDistrict($district)
    {
        $counties = FuelStation::counties($district);
        foreach ($counties as $county) {
            $this->updateCounty($district, $county);
        }
        $district_data = [
            'stations_none'        => 0,
            'stations_partial'     => 0,
            'stations_all'         => 0,
            'stations_no_gasoline' => 0,
            'stations_no_diesel'   => 0,
            'stations_no_lpg'      => 0,
        ];
        $district_stations                     = FuelStation::where([['district','=',$district]]);
        $district_data['stations_none']        = $district_stations->empty()->count();
        $district_data['stations_partial']     = $district_stations->partial()->count();
        $district_data['stations_all']         = $district_stations->withAll()->count();
        $district_data['stations_no_gasoline'] = $district_stations->noGasoline()->count();
        $district_data['stations_no_diesel']   = $district_stations->noDiesel()->count();
        $district_data['stations_no_lpg']      = $district_stations->noLPG()->count();
        Storage::disk('public')->put('data/stats_'.$district.'.json', \json_encode($district_data));
        $this->clearCloudflare(URL::to('/storage/data/stats_'.$district.'.json'));
    }

    public function updateStats()
    {
        $entries = [
            'entries_last_hour' => 0,
            'entries_last_day'  => 0,
            'entries_total'     => 0,
        ];
        $global = [
            'stations_none'        => 0,
            'stations_partial'     => 0,
            'stations_all'         => 0,
            'stations_no_gasoline' => 0,
            'stations_no_diesel'   => 0,
            'stations_no_lpg'      => 0,

        ];
        $entries['entries_last_hour']   = Entry::lastHour()->count();
        $entries['entries_last_day']    = Entry::lastDay()->count();
        $entries['entries_total']       = Entry::all()->count();
        $global['stations_none']        = FuelStation::empty()->count();
        $global['stations_partial']     = FuelStation::partial()->count();
        $global['stations_all']         = FuelStation::withAll()->count();
        $global['stations_no_gasoline'] = FuelStation::noGasoline()->count();
        $global['stations_no_diesel']   = FuelStation::noDiesel()->count();
        $global['stations_no_lpg']      = FuelStation::noLPG()->count();
        Storage::disk('public')->put('data/stats_entries.json', \json_encode($entries));
        Storage::disk('public')->put('data/stats_global.json', \json_encode($global));
        $this->clearCloudflare(URL::to('/storage/data/stats_entries.json'));
        $this->clearCloudflare(URL::to('/storage/data/stats_global.json'));
        $districts = FuelStation::districts();
        foreach ($districts as $district) {
            if ($district != '') {
                $this->updateDistrict($district);
            }
        }
    }

    public function updateStations()
    {
        $json = FuelStation::all('id', 'name', 'brand', 'long', 'lat', 'repa', 'sell_gasoline', 'sell_diesel', 'sell_lpg', 'has_gasoline', 'has_diesel', 'has_lpg')->toJson();
        Storage::disk('public')->put('data/cache.json', $json);
        $this->clearCloudflare(URL::to('/storage/data/cache.json'));
    }
}
