<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Музыкальная группа
 *
 * Class Group
 */
class MusicGroup extends Model
{
    use SoftDeletes;

    protected $table = 'music_group';

    protected $fillable = [
        'name',
        'avatar_group',
        'career_start_year',
        'type_music_group_id',
        'genre_id',
        'description',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function genre(){
        return $this->hasOne(Genre::class);
    }
}
