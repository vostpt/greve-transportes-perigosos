<?php

declare(strict_types=1);

namespace App\Models;

use App\Jobs\UpdateStationsCacheJob;
use App\Observers\EntryObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([EntryObserver::class])]
class Entry extends Model
{
    protected $table = 'entries';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'has_gasoline' => 'bool',
            'has_diesel' => 'bool',
            'has_lpg' => 'bool',
            'used' => 'bool',
        ];
    }

    public function fuelStation(): BelongsTo
    {
        return $this->belongsTo(FuelStation::class, 'fuel_station');
    }

    public function scopeRelated(Builder $query, int $fuelStation, bool $hasGasoline, bool $hasDiesel, bool $hasLpg, ?string $ip): Builder
    {
        return $query
            ->where('fuel_station', $fuelStation)
            ->where('has_gasoline', $hasGasoline)
            ->where('has_diesel', $hasDiesel)
            ->where('has_lpg', $hasLpg)
            ->where('ip', '!=', $ip);
    }

    public function scopeRelatedAny(Builder $query, int $fuelStation, bool $hasGasoline, bool $hasDiesel, bool $hasLpg): Builder
    {
        return $query
            ->where('fuel_station', $fuelStation)
            ->where('has_gasoline', $hasGasoline)
            ->where('has_diesel', $hasDiesel)
            ->where('has_lpg', $hasLpg);
    }

    public function scopeLastHour(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subHour());
    }

    public function scopeLastDay(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->subDay());
    }

    public function scopeNotUsed(Builder $query): Builder
    {
        return $query->where('used', false);
    }

    public function scopeUsed(Builder $query): Builder
    {
        return $query->where('used', true);
    }

    public function promote(): void
    {
        static::query()
            ->relatedAny($this->fuel_station, $this->has_gasoline, $this->has_diesel, $this->has_lpg)
            ->update(['used' => true]);

        $this->fuelStation()->update([
            'has_gasoline' => $this->has_gasoline,
            'has_diesel' => $this->has_diesel,
            'has_lpg' => $this->has_lpg,
        ]);

        UpdateStationsCacheJob::dispatch();
    }
}
