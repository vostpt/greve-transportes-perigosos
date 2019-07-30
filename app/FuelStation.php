<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelStation extends Model
{
    protected $table = 'fuel_stations';

    protected $guarded = ['id'];

    public function getStringAttribute()
    {
        return "{$this->name} ({$this->brand}) ID #{$this->id}";
    }

    public function scopeEmpty($query)
    {
        return $query->noGasoline()->noDiesel()->noLPG();
    }

    public function scopeNoGasoline($query)
    {
        return $query->where([['sell_gasoline', '=', true], ['has_gasoline','=',false]]);
    }

    public function scopeNoDiesel($query)
    {
        return $query->where([['sell_diesel', '=', true], ['has_diesel','=',false]]);
    }

    public function scopeNoLPG($query)
    {
        return $query->where([['sell_lpg', '=', true], ['has_lpg','=',false]]);
    }
}
