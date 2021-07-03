<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Variasi extends Model
{
    protected $table = 'master_variasi';

    protected $fillable = [
        'id', 'name','status'
    ];
}
