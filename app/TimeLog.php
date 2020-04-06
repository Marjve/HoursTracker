<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    protected $table = 'timeLog';

    protected $fillable = [
        'check_in', 'check_out', 'hours',
    ];
}
