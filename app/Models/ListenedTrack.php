<?php

namespace App\Models;

use App\Models\Traits\UsesUuid;
use App\User;
use Illuminate\Database\Eloquent\Model;

class ListenedTrack extends Model
{
    use UsesUuid;
    protected $table = 'listened_tracks';

    protected $fillable = ['user_id', 'track_id'];

    public function products()
    {
        return $this->belongsTo(Track::class, 'track_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
