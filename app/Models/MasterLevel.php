<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterLevel extends Model
{
    protected $table = 'master_level';

    protected $fillable = [
        'id', 'level'
    ];
}
