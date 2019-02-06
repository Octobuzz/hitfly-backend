<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Жанры музыки
 *
 * Class Genre
 */
class Genre extends Model
{
    use SoftDeletes;

    protected $table = 'genres';

    protected $fillable = [
        'name',
        'image',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];
}
