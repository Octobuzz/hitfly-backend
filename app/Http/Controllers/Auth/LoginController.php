<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SocialAccountService;
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
    protected $redirectTo = '/home';

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

        Auth::login($user);
        Auth::guard('json')->login($user);
        if (null !== $user->email && false === $user->hasVerifiedEmail()) {
            VerificationController::sendNotification($user);
        }
        $user->markEmailAsVerified();

        return redirect()->to('/register-genres');
    }
}
