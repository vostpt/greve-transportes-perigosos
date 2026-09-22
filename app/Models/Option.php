<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $primaryKey = 'name';

    public $timestamps = false;

    protected $fillable = ['value'];

    public static function get(string $name, ?string $default = null): ?string
    {
        $option = static::query()->find($name);

        return $option !== null ? $option->value : $default;
    }

    public static function set(string $name, string $value): void
    {
        static::query()->where('name', $name)->update(['value' => $value]);
    }
}
