<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $table = 'master_toko';

    protected $fillable = [
        'id', 'status','id_user','isdelete','created_by','updated_by','delete_by'
    ];
}
