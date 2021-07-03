<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKurir extends Model
{
    protected $table = 'master_kurir';

    protected $fillable = [
        'id', 'id_users','status','notif'
    ];
}
