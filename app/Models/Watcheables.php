<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 29.03.19
 * Time: 14:44.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Watcheables extends Model
{
    protected $table = 'watcheables';

    protected $fillable = [
        'watcheable_type',
        'watcheable_id',
        'user_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function watcheable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(MusicGroup::class, 'watcheable_id');
    }
}
