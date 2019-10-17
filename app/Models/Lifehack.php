<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\PictureField;

/**
 * @property string title
 */
class Lifehack extends Model
{
    use PictureField;
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
}
