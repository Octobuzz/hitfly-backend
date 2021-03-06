<?php

namespace App\Models;

use App\User;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use InvalidArgumentException;

/**
 * App\Models\Like.
 *
 * @property int            $id
 * @property string         $likeable_type
 * @property int            $likeable_id
 * @property Carbon|null    $created_at
 * @property Carbon|null    $updated_at
 * @property int            $user_id
 * @property Model|Eloquent $likeable
 * @property User           $user
 * @property Lifehack       $lifehack
 *
 * @method static Builder|Like newModelQuery()
 * @method static Builder|Like newQuery()
 * @method static Builder|Like query()
 * @method static Builder|Like whereCreatedAt($value)
 * @method static Builder|Like whereId($value)
 * @method static Builder|Like whereLikeableId($value)
 * @method static Builder|Like whereLikeableType($value)
 * @method static Builder|Like whereUpdatedAt($value)
 * @method static Builder|Like whereUserId($value)
 * @mixin Eloquent
 */
class Like extends Model
{
    /** @var string Типы лайков, при добавлении нового типа дополнить метод self::getTypeClass() */
    public const TYPE_LIFE_HACK = 'life_hack';

    protected $fillable = [
        'likeable',
        'likeable_type',
        'likeable_id',
        'user_id',
    ];

    /**
     * @return MorphTo
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function lifehack(): BelongsTo
    {
        return $this->belongsTo(Lifehack::class, 'likeable_id');
    }

    /**
     * По заданному типу лайка $type вернет связанное имя класса.
     *
     * @param string $type Тип лайка (одна из self::TYPE_*) констант.
     * @return string
     * @throws InvalidArgumentException
     */
    public static function getTypeClass(string $type): string
    {
        switch ($type) {
            case Like::TYPE_LIFE_HACK:
                /** @var Lifehack $class */
                $class = Lifehack::class;
                break;
            default:
                throw new InvalidArgumentException('Не удалось определить тип лайка');
        }
        return  $class;
    }
}
