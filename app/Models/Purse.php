<?php

namespace App\Models;

use App\User;
use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Purse.
 *
 * @method static Builder|Purse newModelQuery()
 * @method static Builder|Purse newQuery()
 * @method static Builder|Purse query()
 * @mixin Eloquent
 *
 * @property int    $id
 * @property int    $balance
 * @property int    $user_id
 * @property int    $operation_id
 * @property string $name
 *
 * @method static Builder|Purse whereBalance($value)
 * @method static Builder|Purse whereCurrencyId($value)
 * @method static Builder|Purse whereId($value)
 * @method static Builder|Purse whereName($value)
 * @method static Builder|Purse whereOperationId($value)
 * @method static Builder|Purse whereUserId($value)
 *
 * @property \Illuminate\Database\Eloquent\Collection|Operation[] $operations
 */
class Purse extends Model
{
    public const NAME_BONUS = 'bonus';

    protected $attributes = [
        'balance' => 0,
        'name' => self::NAME_BONUS,
    ];

    protected $table = 'purse';
    public $timestamps = false;

    protected $fillable = ['user_id', 'currency_id', 'balance', 'name'];

    public function increaseBalance($amount): Purse
    {
        $this->balance += $amount;

        return $this;
    }

    public function decreaseBalance($amount): Purse
    {
        $this->balance -= $amount;

        return $this;
    }

    public function operations(): HasMany
    {
        return $this->hasMany(Operation::class);
    }

    /**
     * @param Operation $operation
     *
     * @return bool
     *
     * @throws Exception
     */
    public function processOperation(Operation $operation): bool
    {
        switch ($operation->direction) {
            case Operation::DIRECTION_DECREASE:
                if ($this->balance < $operation->amount) {
                    return false;
                }
                $this->decreaseBalance($operation->amount);
                break;
            case Operation::DIRECTION_INCREASE:
                $this->increaseBalance($operation->amount);
                break;
            default:
                throw new Exception('IncorrectOperation');
        }
        $operation->purseBonus()->associate($this);
        $operation->save();
        $this->save();

        return true;
    }

    public function hasPositiveBalance()
    {
        if ($this->balance > 0) {
            return true;
        }

        return false;
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
