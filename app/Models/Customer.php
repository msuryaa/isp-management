<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'tb_pelanggan';
  
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'phone',
        'address',
        'internet_package',
        'package_price',
        'status',
    ];
}
