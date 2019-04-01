<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'area_region',
        'lat',
        'long',
        'lower_corner',
        'upper_corner',
    ];

    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->hasMany(\App\User::class);
    }
}
