<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'master_category';

    protected $fillable = [
        'id', 'category_name','category_icon','isdelete','id_variasi','status'
    ];
}
