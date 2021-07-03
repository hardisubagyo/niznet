<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'master_brand';

    protected $fillable = [
        'id', 'brand_name','brand_icon','isdelete','status'
    ];
}
