<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExternalAuth extends Model
{
    use SoftDeletes;

    //
    protected $table = 'external_auth';

    protected $guarded = ['id'];
}
