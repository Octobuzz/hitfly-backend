<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Charts.
 *
 * @property int                             $id
 * @property int                             $track_id
 * @property int                             $weekly_rate
 * @property int                             $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \App\Models\Track               $track
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Charts whereWeeklyRate($value)
 * @mixin \Eloquent
 */
class Charts extends Model
{
    protected $fillable = [
        'track_id',
        'weekly_rate',
        'rating',
    ];

    public function track()
    {
        return $this->belongsTo(Track::class);
    }
}
