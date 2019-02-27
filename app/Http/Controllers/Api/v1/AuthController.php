<?php
/**
 * Created by PhpStorm.
 * User: georgio
 * Date: 14.02.19
 * Time: 14:39.
 */

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $guard = 'json';

    public function getAuthenticatedUser()
    {
        $user = Auth::user();

        return response()->json($user);
    }
}
