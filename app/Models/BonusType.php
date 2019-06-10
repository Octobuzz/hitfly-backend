<?php

namespace App\Models;

use App\Interfaces\BonusProgramTypesInterfaces;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BonusType.
 *
 * @property int    $id
 * @property string $name
 * @property string $constant_name
 * @property int    $bonus
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereConstantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereName($value)
 * @mixin \Eloquent
 */
class BonusType extends Model implements BonusProgramTypesInterfaces
{
}
