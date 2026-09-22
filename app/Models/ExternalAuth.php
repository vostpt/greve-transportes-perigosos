<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalAuth extends Model
{
    use SoftDeletes;

    public const BRAND_READONLY = 'READONLY';

    public const BRAND_WRITEREAD = 'WRITEREAD';

    protected $table = 'external_auth';

    protected $guarded = ['id'];

    protected $hidden = ['secret'];

    public static function authenticate(?string $key, ?string $secret): ?self
    {
        if (! $key || ! $secret) {
            return null;
        }

        return static::query()
            ->where('key', $key)
            ->where('secret', $secret)
            ->first();
    }

    public function isReadonly(): bool
    {
        return $this->brand === self::BRAND_READONLY;
    }

    public function canWriteAny(): bool
    {
        return $this->brand === self::BRAND_WRITEREAD;
    }

    public function canReadAll(): bool
    {
        return $this->isReadonly() || $this->canWriteAny();
    }

    public function ownsStation(FuelStation $station): bool
    {
        return $this->canWriteAny()
            || $station->brand === $this->brand
            || $station->brand_management === $this->brand;
    }
}
