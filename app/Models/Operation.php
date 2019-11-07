<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Operation.
 *
 * @method static Builder|Operation newModelQuery()
 * @method static Builder|Operation newQuery()
 * @method static Builder|Operation query()
 * @mixin \Eloquent
 *
 * @property Purse                           $purse
 * @property int                             $id
 * @property string                          $direction
 * @property float                           $amount
 * @property string                          $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null                     $deleted_at
 * @property int                             $purse_id
 * @property string|null                     $transaction
 *
 * @method static Builder|Operation whereAmount($value)
 * @method static Builder|Operation whereCreatedAt($value)
 * @method static Builder|Operation whereDeletedAt($value)
 * @method static Builder|Operation whereDescription($value)
 * @method static Builder|Operation whereDirection($value)
 * @method static Builder|Operation whereId($value)
 * @method static Builder|Operation wherePurseId($value)
 * @method static Builder|Operation whereTransaction($value)
 * @method static Builder|Operation whereUpdatedAt($value)
 *
 * @property int|null $type_id
 *
 * @method static Builder|Operation whereTypeId($value)
 *
 * @property string|null $extra_data
 * @property Purse       $purseBonus
 *
 * @method static Builder|Operation whereExtraData($value)
 */
class Operation extends Model
{
    public const DIRECTION_INCREASE = '+';
    public const DIRECTION_DECREASE = '-';

    protected $table = 'operation';

    protected $fillable = ['direction', 'amount', 'description', 'type_id', 'extra_data'];

    public function purseBonus(): BelongsTo
    {
        return $this->belongsTo(Purse::class, 'purse_id');
    }

    public static function countOperation(BonusType $bonusType, User $user, $extraData = null): int
    {
        $purse = $user->purse()->firstOrNew(['user_id' => $user->id, 'name' => Purse::NAME_BONUS]);
        $query = Operation::query()->where('purse_id', '=', $purse->id)->where('type_id', '=', $bonusType->id);

        if (null !== $extraData) {
            $query->where('extra_data', '=', $extraData);
        }

        return $query->count();
    }
}
