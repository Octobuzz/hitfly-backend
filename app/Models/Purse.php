<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Purse.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse query()
 * @mixin \Eloquent
 *
 * @property int    $id
 * @property int    $balance
 * @property int    $user_id
 * @property int    $operation_id
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse whereOperationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purse whereUserId($value)
 *
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Operation[] $operations
 */
class Purse extends Model
{
    const NAME_BONUS = 'bonus';

    protected $attributes = [
        'balance' => 0,
        'name' => self::NAME_BONUS,
    ];

    protected $table = 'purse';
    public $timestamps = false;

    protected $fillable = ['user_id', 'currency_id', 'balance', 'name'];

    public function increaseBalance($amount)
    {
        $this->balance += $amount;

        return $this;
    }

    public function decreaseBalance($amount)
    {
        $this->balance -= $amount;

        return $this;
    }

    public function operations(?string $direction): HasMany
    {
        return $this->hasMany(Operation::class);
    }

    public function processOperation(Operation $operation)
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
                throw new \Exception('IncorrectOperation');
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
}
