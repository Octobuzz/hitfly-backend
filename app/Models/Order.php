<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = ['name', 'description', 'user_id', 'product_id'];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'order_attribute_value')->withPivot('value')->withTimestamps();
    }
}
