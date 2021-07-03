<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'tr_cart';

    protected $fillable = [
        'id', 'id_product','id_user','quantity'
    ];
}
