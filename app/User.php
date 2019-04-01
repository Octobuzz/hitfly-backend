<?php

namespace App;

use App\Models\Album;
use App\Models\City;
use App\Models\Like;
use App\Models\MusicGroup;
use App\Models\Track;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use  Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPassword as ResetPasswordNotification;

/**
 * App\User.
 *
 * @property int                                                                                                       $id
 * @property string                                                                                                    $username
 * @property string                                                                                                    $email
 * @property string|null                                                                                               $email_verified_at
 * @property string                                                                                                    $password
 * @property string|null                                                                                               $remember_token
 * @property \Illuminate\Support\Carbon|null                                                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                                                           $updated_at
 * @property string                                                                                                    $avatar
 * @property string                                                                                                    $access_token
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property \Illuminate\Database\Eloquent\Collection|\Encore\Admin\Auth\Database\Permission[]                         $permissions
 * @property \Illuminate\Database\Eloquent\Collection|\Encore\Admin\Auth\Database\Role[]                               $roles
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Administrator implements JWTSubject, CanResetPasswordContract
{
    use Notifiable;
    use CanResetPassword;

    const GENDER_MEN = 'M';
    const GENDER_WOMEN = 'F';

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->access_token = md5(microtime());
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'gender',
        'birthday',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function generateAccessToken()
    {
        $this->access_token = md5(microtime());
    }

    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class, 'user_track', 'user_id', 'track_id')->withPivot('listen_counts')->withTimestamps();
    }

    public function musicGroups(): HasMany
    {
        return $this->hasMany(MusicGroup::class, 'creator_group_id');
    }

    public function watchingUsers()
    {
        return new Collection([]);
    }

    public function likesTrack()
    {
        return $this->hasMany(Like::class)->where('likeable_type', '=', Track::class);
    }

    public function likesAlbum()
    {
        return $this->hasMany(Like::class)->where('likeable_type', '=', Album::class);
    }

    public function watchingUser()
    {
        return $this->morphedByMany(User::class, 'watcheable');
    }

    public function watchingMusicGroup()
    {
        return $this->morphedByMany(MusicGroup::class, 'watcheable');
    }

    public function sendPasswordResetNotification($token)
    {
        // Your your own implementation.
        $this->notify(new ResetPasswordNotification($token));
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
