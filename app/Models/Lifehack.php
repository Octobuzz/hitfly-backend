<?php

namespace App\Models;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\PictureField;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property string title
 */
class Lifehack extends Model
{
    use PictureField, GraphQLAuthTrait;

    protected $table = 'lifehacks';
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = ['title', 'image', 'sort'];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'lifehack_tag');
    }

    public function getImage(): ?string
    {
        return $this->getOriginal('image');
    }

    public function getPath(): string
    {
        return $this->user_id.'/';
    }

    public function favorite(): MorphMany
    {
        $user = $this->getGuard()->user();

        return $this
            ->morphMany(Favourite::class, 'favouriteable')
            ->where('user_id', null === $user ? null : $user->id);
    }

    public function like(): MorphMany
    {
        $user = $this->getGuard()->user();

        return $this
            ->morphMany(Like::class, 'likeable')
            ->where('user_id', null === $user ? null : $user->id);
    }

    public function likeCount(): int
    {
        return $this->morphMany(Like::class, 'likeable')->count();
    }
}
