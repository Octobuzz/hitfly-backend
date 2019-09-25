<?php

namespace App\Models;

use App\Models\Traits\PictureField;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use PictureField;
    protected $table = 'products';

    protected $fillable = ['name', 'description', 'alias', 'price'];

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attribute')->withTimestamps();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getPath(): string
    {
        return 'products/'.$this->id.'/';
    }
}
