<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\UserRegisterJob;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth:json')->except('verify');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
        if ($request->filled('redirect')) {
            $this->redirectTo = $request->redirect;
        }
        if ($request->user() && $request->user() != $request->route('id')) {
            Auth::guard()->logout();
        }

        if (!$request->user()) {
            Auth::guard()->loginUsingId($request->route('id'), true); //todo не удается получить пользоватея из ссылки(но перед верификацией ссылка проходит свою верификацию)
        }

        if ($request->route('id') != $request->user()->getKey()) {
            throw new AuthorizationException();
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        if ($request->user()->markEmailAsVerified()) {
            $this->sendNotification($request->user());
            event(new Verified($request->user()));
        }

        return redirect($this->redirectPath())->with('verified', true);
    }

    public static function sendNotification($user)
    {
        //отправка письма о завершении регистрации

        if ($user->email) {
            dispatch(new UserRegisterJob($user))->onQueue('low');
        }
    }
}
