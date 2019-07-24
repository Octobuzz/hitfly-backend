<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['name', 'description', 'alias', 'price'];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute')->withTimestamps();
    }
}
