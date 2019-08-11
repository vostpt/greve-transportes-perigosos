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
        return $query->whereRaw('(((sell_gasoline = false) || (sell_gasoline = true && has_gasoline = false)) AND ((sell_diesel = false) || (sell_diesel = true && has_diesel = false)) AND((sell_lpg = false) || (sell_lpg = true && has_lpg = false)))');
    }

    public function scopePartial($query)
    {
        return $query->whereRaw('(!(((sell_gasoline = false) || (sell_gasoline = true && has_gasoline = true)) AND ((sell_diesel = false) || (sell_diesel = true && has_diesel = true)) AND ((sell_lpg = false) || (sell_lpg = true && has_lpg = true))) AND !(((sell_gasoline = false) || (sell_gasoline = true && has_gasoline = false)) AND ((sell_diesel = false) || (sell_diesel = true && has_diesel = false)) AND((sell_lpg = false) || (sell_lpg = true && has_lpg = false))))');
    }

    public function scopeWithAll($query)
    {
        return $query->whereRaw('(((sell_gasoline = false) || (sell_gasoline = true && has_gasoline = true)) AND((sell_diesel = false) || (sell_diesel = true && has_diesel = true)) AND ((sell_lpg = false) || (sell_lpg = true && has_lpg = true)))');
    }

    public function scopeGasoline($query)
    {
        return $query->where([['sell_gasoline', '=', true], ['has_gasoline','=',true]])->orWhere([['sell_gasoline', '=', false]]);
    }

    public function scopeDiesel($query)
    {
        return $query->where([['sell_diesel', '=', true], ['has_diesel','=',true]])->orWhere([['sell_diesel', '=', false]]);
    }

    public function scopeLPG($query)
    {
        return $query->where([['sell_lpg', '=', true], ['has_lpg','=',true]])->orWhere([['sell_lpg', '=', false]]);
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

    public function scopeDistricts($query)
    {
        return $query->selectRaw('district')->groupBy('district')->get()->pluck('district');
    }

    public function scopeCounties($query, $district)
    {
        return $query->selectRaw('county')->where('district', '=', $district)->groupBy('county')->get()->pluck('county');
    }

    public function scopeSellGasoline($query)
    {
        return $query->where([['sell_gasoline', '=', true]]);
    }

    public function scopeSellDiesel($query)
    {
        return $query->where([['sell_diesel', '=', true],]);
    }

    public function scopeSellLPG($query)
    {
        return $query->where([['sell_lpg', '=', true]]);
    }

    public function scopeBrands($query)
    {
        return $query->selectRaw('brand')->groupBy('brand')->get()->pluck('brand');
    }
}
