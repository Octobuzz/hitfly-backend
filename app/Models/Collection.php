<?php

namespace App\Models;

use App\Models\Traits\Itemable;
use App\Support\FileProcessingTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    use Itemable, FileProcessingTrait;

    protected $nameFolder = 'collection';

    protected $fillable = [
        'image',
        'title',
        'user_id',
        'is_admin',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class, 'collection_track')->withTimestamps();
    }

    public function getImageAttribute($nameAttribute): ?string
    {
        return $this->getUrlFile($nameAttribute);
    }
}
