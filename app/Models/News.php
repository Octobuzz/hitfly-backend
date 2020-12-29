<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string title
 */
class News extends Model
{
    protected $table = 'news';

    protected $fillable = ['title', 'content'];
}
