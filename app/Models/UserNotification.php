<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 08.02.19
 * Time: 11:27.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Album.
 */
class UserNotification extends Model
{
    protected $table = 'user_notifications';

    public $timestamps = false;

    protected $fillable = [
        'token_web_socket',
        'token_push',
    ];
}
