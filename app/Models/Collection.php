<?php

namespace App\Models;

use App\Models\Traits\Itemable;
use App\Models\Traits\PictureField;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Collection extends Model
{
    use Itemable, PictureField;

    protected $nameFolder = 'collection';

    protected $fillable = [
        'image',
        'title',
        'user_id',
        'is_admin',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('image', function (Builder $builder) {
            $builder->addSelect('image');
        });
    }

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
        return Storage::disk('admin')->url($nameAttribute);
    }

    public function userFavourite()
    {
        return $this->morphMany(Favourite::class, 'favouriteable')->where('user_id', \Auth::user()->id);
    }

    public function favourites()
    {
        return $this->morphMany(Favourite::class, 'favouriteable');
    }

    public function getPath(): string
    {
        return 'collection/'.$this->user_id.'/';
    }

    public function getImage(): ?string
    {
        return $this->getOriginal('image');
    }
}
