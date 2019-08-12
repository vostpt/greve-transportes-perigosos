<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Entry;
use App\FuelStation;
use Carbon\Carbon;
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
            \usleep(500 * 1000); // Prevent hitting Cloudflare Max Requests Limit
        }
    }

    private function updateBrandsStats()
    {
        $brands       = FuelStation::brands();
        $brands_stats = [];
        foreach ($brands as $brand) {
            $brands_stats[$brand] = [
                'stations_total'         => FuelStation::where([['brand','=',$brand]])->count(),
                'stations_none'          => FuelStation::where([['brand','=',$brand]])->empty()->count(),
                'stations_partial'       => FuelStation::where([['brand','=',$brand]])->partial()->count(),
                'stations_all'           => FuelStation::where([['brand','=',$brand]])->withAll()->count(),
                'stations_no_gasoline'   => FuelStation::where([['brand','=',$brand]])->noGasoline()->count(),
                'stations_no_diesel'     => FuelStation::where([['brand','=',$brand]])->noDiesel()->count(),
                'stations_no_lpg'        => FuelStation::where([['brand','=',$brand]])->noLPG()->count(),
                'stations_sell_gasoline' => FuelStation::where([['brand','=',$brand]])->sellGasoline()->count(),
                'stations_sell_diesel'   => FuelStation::where([['brand','=',$brand]])->sellDiesel()->count(),
                'stations_sell_lpg'      => FuelStation::where([['brand','=',$brand]])->sellLPG()->count(),
            ];
        }
        Storage::disk('public')->put('data/stats_brands.json', \json_encode($brands_stats));
        $this->clearCloudflare(URL::to('/storage/data/stats_brands.json'));
    }

    private function updateCounty($district, $county)
    {
        $county_data = [
            'stations_total'         => FuelStation::where([['district','=',$district], ['county','=',$county]])->count(),
            'stations_none'          => FuelStation::where([['district','=',$district], ['county','=',$county]])->empty()->count(),
            'stations_partial'       => FuelStation::where([['district','=',$district], ['county','=',$county]])->partial()->count(),
            'stations_all'           => FuelStation::where([['district','=',$district], ['county','=',$county]])->withAll()->count(),
            'stations_no_gasoline'   => FuelStation::where([['district','=',$district], ['county','=',$county]])->noGasoline()->count(),
            'stations_no_diesel'     => FuelStation::where([['district','=',$district], ['county','=',$county]])->noDiesel()->count(),
            'stations_no_lpg'        => FuelStation::where([['district','=',$district], ['county','=',$county]])->noLPG()->count(),
            'stations_sell_gasoline' => FuelStation::where([['district','=',$district], ['county','=',$county]])->sellGasoline()->count(),
            'stations_sell_diesel'   => FuelStation::where([['district','=',$district], ['county','=',$county]])->sellDiesel()->count(),
            'stations_sell_lpg'      => FuelStation::where([['district','=',$district], ['county','=',$county]])->sellLPG()->count(),
        ];
        Storage::disk('public')->put('data/stats_'.\ucfirst(\mb_strtolower($district)).'_'.$county.'.json', \json_encode($county_data));
        $this->clearCloudflare(URL::to('/storage/data/stats_'.\ucfirst(\mb_strtolower($district)).'_'.$county.'.json'));
    }

    private function updateDistrict($district)
    {
        $district_data = [
            'stations_total'         => FuelStation::where([['district','=',$district]])->count(),
            'stations_none'          => FuelStation::where([['district','=',$district]])->empty()->count(),
            'stations_partial'       => FuelStation::where([['district','=',$district]])->partial()->count(),
            'stations_all'           => FuelStation::where([['district','=',$district]])->withAll()->count(),
            'stations_no_gasoline'   => FuelStation::where([['district','=',$district]])->noGasoline()->count(),
            'stations_no_diesel'     => FuelStation::where([['district','=',$district]])->noDiesel()->count(),
            'stations_no_lpg'        => FuelStation::where([['district','=',$district]])->noLPG()->count(),
            'stations_sell_gasoline' => FuelStation::where([['district','=',$district]])->sellGasoline()->count(),
            'stations_sell_diesel'   => FuelStation::where([['district','=',$district]])->sellDiesel()->count(),
            'stations_sell_lpg'      => FuelStation::where([['district','=',$district]])->sellLPG()->count(),
        ];
        Storage::disk('public')->put('data/stats_'.\ucfirst(\mb_strtolower($district)).'.json', \json_encode($district_data));
        $this->clearCloudflare(URL::to('/storage/data/stats_'.\ucfirst(\mb_strtolower($district)).'.json'));
    }

    public function updateStats()
    {
        $places = [

        ];
        $entries = [
            'entries_last_hour' => 0,
            'entries_last_day'  => 0,
            'entries_total'     => 0,
        ];
        $global = [
            'stations_total'         => 0,
            'stations_none'          => 0,
            'stations_partial'       => 0,
            'stations_all'           => 0,
            'stations_no_gasoline'   => 0,
            'stations_no_diesel'     => 0,
            'stations_no_lpg'        => 0,
            'stations_sell_gasoline' => 0,
            'stations_sell_diesel'   => 0,
            'stations_sell_lpg'      => 0,

        ];
        $entries['entries_last_hour']     = Entry::lastHour()->count();
        $entries['entries_last_day']      = Entry::lastDay()->count();
        $entries['entries_total']         = Entry::all()->count();
        $global['stations_total']         = FuelStation::all()->count();
        $global['stations_none']          = FuelStation::empty()->count();
        $global['stations_partial']       = FuelStation::partial()->count();
        $global['stations_all']           = FuelStation::withAll()->count();
        $global['stations_no_gasoline']   = FuelStation::noGasoline()->count();
        $global['stations_no_diesel']     = FuelStation::noDiesel()->count();
        $global['stations_no_lpg']        = FuelStation::noLPG()->count();
        $global['stations_sell_gasoline'] = FuelStation::sellGasoline()->count();
        $global['stations_sell_diesel']   = FuelStation::sellDiesel()->count();
        $global['stations_sell_lpg']      = FuelStation::sellLPG()->count();
        Storage::disk('public')->put('data/stats_entries.json', \json_encode($entries));
        Storage::disk('public')->put('data/stats_global.json', \json_encode($global));
        $this->clearCloudflare(URL::to('/storage/data/stats_entries.json'));
        $this->clearCloudflare(URL::to('/storage/data/stats_global.json'));
        $districts = FuelStation::districts();
        foreach ($districts as $district) {
            if ($district != '') {
                $formated_district = \ucfirst(\mb_strtolower($district));
                $this->updateDistrict($district);
                $counties = FuelStation::counties($district);
                foreach ($counties as $county) {
                    if (! \array_key_exists($formated_district, $places)) {
                        $places[$formated_district] = [];
                    }
                    \array_push($places[$formated_district], $county);
                    $this->updateCounty($district, $county);
                }
            }
        }
        Storage::disk('public')->put('data/places.json', \json_encode($places));
        $this->clearCloudflare(URL::to('/storage/data/places.json'));
        $this->updateBrandsStats();
    }

    public function updateStations()
    {
        $json = FuelStation::all('id', 'name', 'brand', 'long', 'lat', 'repa', 'sell_gasoline', 'sell_diesel', 'sell_lpg', 'has_gasoline', 'has_diesel', 'has_lpg')->toJson();
        Storage::disk('public')->put('data/cache.json', $json);
        $this->clearCloudflare(URL::to('/storage/data/cache.json'));
    }

    public function updateStatsBeginnig()
    {
        $firstDate = Entry::get()->first()->created_at;

        $firstDate = $firstDate->toImmutable();
        $nextDate  = $firstDate;
        $nextDate  = $nextDate->addHour();

        $entries = [];

        while ($firstDate <= Carbon::now()) {
            $entries[$firstDate->toDateTimeString()] = Entry::where([['created_at', '>',  $firstDate],
                                ['created_at', '<', $nextDate], ])
                                ->count();

            $nextDate  = $nextDate->addHour();
            $firstDate = $firstDate->addHour();
        }

        Storage::disk('public')->put('data/stats_entries_hourly.json', \json_encode($entries));
        $this->clearCloudflare(URL::to('/storage/data/stats_entries_hourly.json'));
    }
}
