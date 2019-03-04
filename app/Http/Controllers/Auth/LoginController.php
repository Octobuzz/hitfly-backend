<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\ApiResponseTrait;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ApiResponseTrait;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect to provider for authentication
     *
     * @param $driver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($driver)
    {
        if (! config()->has("services.{$driver}") ){
            return $this->sendFailedResponse("Драйвер {$driver} не поддерживается", 204);
        }

        try {
            return Socialite::with($driver)->redirect();
        } catch (Exception $e){
            return $this->sendFailedResponse($e->getMessage());
        }
    }

    /**
     * Login social user
     *
     * @param string $provider
     * @return
     */
    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        return view('auth.register', ['socialUser' => $socialUser]);
    }}


