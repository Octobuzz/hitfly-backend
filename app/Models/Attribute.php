<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';

    protected $fillable = ['name', 'alias'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attribute', 'product_id');
    }
}
