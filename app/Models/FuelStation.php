<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class FuelStation extends Model
{
    protected $table = 'fuel_stations';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'long' => 'float',
            'repa' => 'bool',
            'sell_gasoline' => 'bool',
            'sell_diesel' => 'bool',
            'sell_lpg' => 'bool',
            'has_gasoline' => 'bool',
            'has_diesel' => 'bool',
            'has_lpg' => 'bool',
            'ense_gasoline' => 'float',
            'ense_diesel' => 'float',
        ];
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class, 'fuel_station');
    }

    public function getStringAttribute(): string
    {
        return "{$this->name} ({$this->brand}) ID #{$this->id}";
    }

    public function scopeEmpty(Builder $query): Builder
    {
        return $query->whereRaw('(((sell_gasoline = false) OR (sell_gasoline = true AND has_gasoline = false)) AND ((sell_diesel = false) OR (sell_diesel = true AND has_diesel = false)) AND ((sell_lpg = false) OR (sell_lpg = true AND has_lpg = false)))');
    }

    public function scopePartial(Builder $query): Builder
    {
        return $query->whereRaw('(NOT (((sell_gasoline = false) OR (sell_gasoline = true AND has_gasoline = true)) AND ((sell_diesel = false) OR (sell_diesel = true AND has_diesel = true)) AND ((sell_lpg = false) OR (sell_lpg = true AND has_lpg = true))) AND NOT (((sell_gasoline = false) OR (sell_gasoline = true AND has_gasoline = false)) AND ((sell_diesel = false) OR (sell_diesel = true AND has_diesel = false)) AND ((sell_lpg = false) OR (sell_lpg = true AND has_lpg = false))))');
    }

    public function scopeWithAll(Builder $query): Builder
    {
        return $query->whereRaw('(((sell_gasoline = false) OR (sell_gasoline = true AND has_gasoline = true)) AND ((sell_diesel = false) OR (sell_diesel = true AND has_diesel = true)) AND ((sell_lpg = false) OR (sell_lpg = true AND has_lpg = true)))');
    }

    public function scopeNoGasoline(Builder $query): Builder
    {
        return $query->where('sell_gasoline', true)->where('has_gasoline', false);
    }

    public function scopeNoDiesel(Builder $query): Builder
    {
        return $query->where('sell_diesel', true)->where('has_diesel', false);
    }

    public function scopeNoLPG(Builder $query): Builder
    {
        return $query->where('sell_lpg', true)->where('has_lpg', false);
    }

    public function scopeSellGasoline(Builder $query): Builder
    {
        return $query->where('sell_gasoline', true);
    }

    public function scopeSellDiesel(Builder $query): Builder
    {
        return $query->where('sell_diesel', true);
    }

    public function scopeSellLPG(Builder $query): Builder
    {
        return $query->where('sell_lpg', true);
    }

    public static function districts(): Collection
    {
        return static::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->pluck('district');
    }

    public static function counties(string $district): Collection
    {
        return static::query()
            ->where('district', $district)
            ->whereNotNull('county')
            ->where('county', '!=', '')
            ->distinct()
            ->pluck('county');
    }

    public static function brands(): Collection
    {
        return static::query()->distinct()->pluck('brand');
    }
}
