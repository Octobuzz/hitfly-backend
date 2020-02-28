<?php

namespace App;

use App\Http\GraphQL\Traits\GraphQLAuthTrait;
use App\Models\Album;
use App\Models\ArtistProfile;
use App\Models\City;
use App\Models\Favourite;
use App\Models\Genre;
use App\Models\Lifehack;
use App\Models\ListenedTrack;
use App\Models\MusicGroup;
use App\Models\Purse;
use App\Models\Social;
use App\Models\Track;
use App\Models\Traits\PictureField;
use App\Models\UserNotification;
use App\Models\UserToken;
use App\Models\Watcheables;
use App\Notifications\HitflyVerifyEmail;
use Carbon\Carbon;
use Chelout\RelationshipEvents\Concerns\HasBelongsToManyEvents;
use Chelout\RelationshipEvents\Traits\HasRelationshipObservables;
use Eloquent;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use  Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPassword as ResetPasswordNotification;

/**
 * App\User.
 *
 * @property int                                                   $id
 * @property string                                                $username
 * @property string                                                $email
 * @property string|null                                           $email_verified_at
 * @property string                                                $password
 * @property string|null                                           $remember_token
 * @property \Illuminate\Support\Carbon|null                       $created_at
 * @property \Illuminate\Support\Carbon|null                       $updated_at
 * @property string                                                $avatar
 * @property string                                                $access_token
 * @property DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property Collection|Permission[]                               $permissions
 * @property Collection|Role[]                                     $roles
 * @property Purse                                                 $purse
 * @property Purse                                                 $purseBonus
 * @property Carbon                                                $last_entrance_app
 *
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @mixin Eloquent
 */
class User extends Administrator implements JWTSubject, CanResetPasswordContract, MustVerifyEmail
{
    use Notifiable;
    use CanResetPassword;
    use SoftDeletes;
    use CascadeSoftDeletes;
    use HasRelationshipObservables;
    use HasBelongsToManyEvents;
    use GraphQLAuthTrait;
    use PictureField;

    protected $cascadeDeletes = [
        'socialsConnect',
        'tracks',
        'musicGroups',
        'likesAlbum',
        'likesGenre',
        'artistProfile',
        'likesTrack',
        'watchUser',
        'watchMusicGroup',
    ];

    const GENDER_MEN = 'M';
    const GENDER_WOMEN = 'F';

    const LEVEL_NOVICE = 'LEVEL_NOVICE'; // Новичок
    const LEVEL_AMATEUR = 'LEVEL_AMATEUR'; // Любитель
    const LEVEL_CONNOISSEUR_OF_THE_GENRE = 'LEVEL_CONNOISSEUR_OF_THE_GENRE'; // Знаток жанра
    const LEVEL_SUPER_MUSIC_LOVER = 'LEVEL_SUPER_MUSIC_LOVER'; // Супер меломан

//    public $redirect; //используется для редиректа на нужную страницу после добавления email в UserObserver(сбор почтовых адресов)

    public $levels = [
        self::LEVEL_NOVICE,
        self::LEVEL_AMATEUR,
        self::LEVEL_CONNOISSEUR_OF_THE_GENRE,
        self::LEVEL_SUPER_MUSIC_LOVER,
    ];

    protected $attributes = [
        'level' => self::LEVEL_NOVICE,
    ];

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
        'city_id',
        'level',
        //'avatar',
        'email_verified_at',
    ];

    //protected $appends = ['avatar'];

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

    public function userTokens(): HasMany
    {
        return $this->hasMany(UserToken::class, 'user_id');
    }

    public function tracks(): HasMany
    {
        return $this->hasMany(Track::class, 'user_id');
    }

    public function musicGroups(): HasMany
    {
        return $this->hasMany(MusicGroup::class, 'creator_group_id');
    }

    public function likesTrack()
    {
        return $this->hasMany(Favourite::class)->where('favouriteable_type', '=', Track::class);
    }

    public function likesAlbum()
    {
        return $this->hasMany(Favourite::class)->where('favouriteable_type', '=', Album::class);
    }

    public function likesGenre()
    {
        return $this->hasMany(Favourite::class)->where('favouriteable_type', '=', Genre::class);
    }

    public function watchUser()
    {
        return $this->hasMany(Watcheables::class, 'watcheable_id');
    }

    public function watchMusicGroup()
    {
        return $this->hasMany(Watcheables::class)->where('watcheable_type', '=', MusicGroup::class);
    }

    public function watchingUser()
    {
        return $this->morphMany(Watcheables::class, 'watcheable');
    }

    /**
     * следит ли текущий пользователь?
     *
     * @return bool
     */
    public function iWatch()
    {
        $watch = Watcheables::query()->where('watcheable_type', '=', User::class)
            ->where('user_id', '=', $this->getGuard()->user()->id)
            ->where('watcheable_id', '=', $this->id)->exists();

        return $watch;
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

    public function favouriteGenres()
    {
        return $this->morphedByMany(Genre::class, 'favouriteable', 'favourites')->withTimestamps();
    }

    //метод без camelCase нужен для работы в админке
    public function favouritegenre()
    {
        return $this->morphedByMany(Genre::class, 'favouriteable', 'favourites')->withTimestamps();
    }

    public function artistProfile()
    {
        return $this->hasOne(ArtistProfile::class);
    }

    public function artist(): HasOne
    {
        return $this->hasOne(ArtistProfile::class, 'user_id');
    }

    public function purse(): HasOne
    {
        return $this->hasOne(Purse::class);
    }

    public function purseBonus(): HasOne
    {
        return $this->hasOne(Purse::class, 'user_id')->where('name', '=', Purse::NAME_BONUS);
    }

    public function userNotification()
    {
        return $this->hasOne(UserNotification::class);
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        if (null !== $this->email_verified_at) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        if (null === $this->email_verified_at) {
            $this->email_verified_at = new Carbon();
            $this->save();

            return true;
        }

        return false;
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification($redirectTo = '')
    {
        $this->notify(new HitflyVerifyEmail($redirectTo));
    }

    public function getImage(): ?string
    {
        return $this->getOriginal('avatar');
    }

    public function getImageUrl(): ?string
    {
        if (null === $this->getImage()) {
            $img = 'avatars/user2-160x160_size_235x235.jpg';
        } else {
            $img = $this->getImage();
        }

        return '/storage/'.$img;
    }

    public function socialsConnect()
    {
        return $this->hasMany(Social::class);
    }

    public function getPath(): string
    {
        return 'avatars/'.$this->id.'/';
    }

    public function followers()
    {
        return $this->morphMany(Watcheables::class, 'watcheable');
    }

    public function myTracksTime(): float
    {
        return $this->tracks()->sum('length');
    }

    public function favouritesTracks()
    {
        return $this->morphedByMany(Track::class, 'favouriteable', 'favourites')->withTimestamps();
    }

    public function favouritesLifehacks()
    {
        return $this->morphedByMany(Lifehack::class, 'favouriteable', 'favourites');
    }

    public function listenedTracks()
    {
        return $this->hasMany(ListenedTrack::class, 'user_id', 'id');
    }

    public function scopefilterRoles($query, array $roles)
    {
        return $query->whereHas('roles', function ($query) use ($roles) {
            $query->whereIn('slug', $roles);
        });
    }

    public function getSizePicture($size): array
    {
        return [config('image.size.avatar.'.$size.'.width'), config('image.size.avatar.'.$size.'.height')];
    }
}
