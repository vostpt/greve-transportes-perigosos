<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelStation extends Model
{
    protected $table = 'fuel_stations';

    protected $guarded = ['id'];
}
