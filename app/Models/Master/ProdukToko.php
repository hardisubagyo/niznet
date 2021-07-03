<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class ProdukToko extends Model
{
    protected $table = 'master_produk_toko';

    protected $fillable = [
        'id','stock','isdelete','status','id_toko','id_master_produk'
    ];
}
