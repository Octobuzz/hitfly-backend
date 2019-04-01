<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 29.03.19
 * Time: 14:44.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watching extends Model
{
    protected $table = 'watching';

    protected $fillable = [
        'likeable',
    ];

    public function watcheable()
    {
        return $this->morphTo();
    }
}
