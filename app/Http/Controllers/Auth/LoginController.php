<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAccountService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\User;
use Exception;
use App\Models\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

    protected function guard()
    {
        return Auth::guard('json');
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect to provider for authentication.
     *
     * @param $driver
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($driver)
    {
        if (!config()->has("services.{$driver}")) {
            return $this->sendFailedResponse("Драйвер {$driver} не поддерживается", 204);
        }

        try {
            return Socialite::with($driver)->redirect();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }
    }

    /**
     * Login social user.
     *
     * @param string $provider
     *
     * @return
     */
    public function handleProviderCallback($provider, SocialAccountService $service, Request $request)
    {
        try {
            switch ($provider) {
                case 'vkontakte':
                    $socialUser = Socialite::driver($provider)->fields(['id', 'email', 'first_name', 'last_name', 'screen_name', 'photo', 'bdate', 'sex'])->stateless()->user();
                    break;
                default:
                    $socialUser = Socialite::driver($provider)->stateless()->user();
            }
        } catch (Exception $e) {
            return redirect()->to('/register-error')->with('message-reg', $e->getMessage());
        }

        $user = $service->loginOrRegisterBySocials($socialUser, $provider);
        $this->guard()->login($user);

        /** @var User $user */
        if (false === $user->hasVerifiedEmail()) {
            event(new Registered($user));
            if (null !== $user->email && false === $user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
                VerificationController::sendNotification($user);
            }
            //$user->markEmailAsVerified();
            //при регистрации редиректим на выбор жанров
            return redirect()->to('/register-genres');
        } else {
            return redirect()->to('/');
        }
    }

    public function loginApi(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->guard()->user();
        } else {
            return false;
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
    }
}
