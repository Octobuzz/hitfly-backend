<?php

namespace App\Models;

use App\Interfaces\BonusProgramTypesInterfaces;
use App\Models\Traits\PictureField;
use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BonusType.
 *
 * @property int    $id
 * @property string $name
 * @property string $constant_name
 * @property int    $bonus
 * @property string description
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereConstantName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BonusType whereName($value)
 * @mixin Eloquent
 */
class BonusType extends Model implements BonusProgramTypesInterfaces
{
    use PictureField;

    public const BONUS_TYPE = 'BONUS_TYPE';

    public $timestamps = false;

    public function getImage(): ?string
    {
        return $this->getOriginal('img');
    }

    public function user()
    {
        return null;
    }
}
