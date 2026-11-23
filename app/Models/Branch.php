<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [ //agar data di simpan 
        'name',
        'address',
        'phone',
    ];
}
