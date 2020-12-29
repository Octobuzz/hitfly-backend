<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string title
 */
class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['name'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Lifehack::class, 'lifehack_tag')->withTimestamps();
    }
}
