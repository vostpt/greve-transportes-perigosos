<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $table = 'options';

    protected $guarded = ['name','description'];

    protected $primaryKey = 'name';

    public $incrementing = false;

    protected $keyType = 'string';
}
