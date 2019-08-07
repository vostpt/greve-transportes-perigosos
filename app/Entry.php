<?php
declare(strict_types=1);

namespace App;

use App\Events\EntryCreated;
use App\Http\Controllers\CacheController;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    //
    protected $table = 'entries';

    protected $guarded = ['id'];

    protected $dispatchesEvents = [
        'created' => EntryCreated::class,
    ];

    public function fuelStation()
    {
        return $this->hasOne('App\FuelStation', 'id', 'fuel_station');
    }

    public function scopeRelated($query, $fuel_station, $has_gasoline, $has_diesel, $has_lpg, $ip)
    {
        return $query->where([['fuel_station','=',$fuel_station],['has_gasoline','=',$has_gasoline],['has_diesel','=',$has_diesel],['has_lpg','=',$has_lpg],['ip','!=',$ip]]);
    }
    
    public function scopeRelatedNoIP($query, $fuel_station, $has_gasoline, $has_diesel, $has_lpg)
    {
        return $query->where([['fuel_station','=',$fuel_station],['has_gasoline','=',$has_gasoline],['has_diesel','=',$has_diesel],['has_lpg','=',$has_lpg]])->get();
    }

    public function scopeLastHour($query)
    {
        return $query->where('created_at', '>=', \Carbon\Carbon::now()->subHour());
    }

    public function scopeLastDay($query)
    {
        return $query->where('created_at', '>=', \Carbon\Carbon::now()->subDay());
    }

    public function scopeNotUsed($query)
    {
        return $query->where('used', '=', '0');
    }

    public function scopeUsed($query)
    {
        return $query->where('used', '=', '1');
    }

    public function push()
    {
        $entries = Entry::relatedNoIP($this->fuel_station, $this->has_gasoline, $this->has_diesel, $this->has_lpg);
        $entries->update(['used' => 1]);
        $fuel_station = $this->fuelStation();
        $fuel_station->update(['has_gasoline' => $this->has_gasoline,'has_diesel' => $this->has_diesel, 'has_lpg' => $this->has_lpg]);
        $cacheController = new CacheController();
        $cacheController->updateStations();
    }
}
