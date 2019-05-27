<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Operation.
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation query()
 * @mixin \Eloquent
 *
 * @property \App\Models\Purse               $purse
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereDirection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation wherePurseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereTransaction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereUpdatedAt($value)
 *
 * @property int|null $type_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Operation whereTypeId($value)
 */
class Operation extends Model
{
    const DIRECTION_INCREASE = '+';
    const DIRECTION_DECREASE = '-';

    protected $table = 'operation';

    protected $fillable = ['direction', 'amount', 'description', 'type_id', 'extra_data'];

    public function purseBonus()
    {
        return $this->belongsTo(Purse::class, 'purse_id');
    }
}
