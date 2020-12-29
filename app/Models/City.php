<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\City.
 *
 * @property int                                                  $id
 * @property string                                               $title
 * @property string|null                                          $area_region
 * @property float|null                                           $lat
 * @property float|null                                           $long
 * @property string|null                                          $lower_corner
 * @property string|null                                          $upper_corner
 * @property \Illuminate\Support\Carbon|null                      $created_at
 * @property \Illuminate\Support\Carbon|null                      $updated_at
 * @property \Illuminate\Support\Carbon|null                      $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereAreaRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereLowerCorner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\City whereUpperCorner($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\City withoutTrashed()
 * @mixin \Eloquent
 */
class City extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'area_region',
        'lat',
        'long',
        'lower_corner',
        'upper_corner',
    ];

    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->hasMany(\App\User::class);
    }
}
