<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'master_bank';

    protected $fillable = [
        'id', 'nama','created_by','updated_by','created_at','updated_at','status'
    ];
}
