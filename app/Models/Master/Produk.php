<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'master_produk';

    protected $fillable = [
        'id', 'product_code','product_name','brand_code','category_code','image1','image2','image3','image4','image5','desc','price','stock','isdelete','variasi','status'
    ];
}
