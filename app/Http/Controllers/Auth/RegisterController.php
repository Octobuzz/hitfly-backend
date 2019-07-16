<?php

namespace App\Http\Controllers\Auth;

use App\Models\Genre;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/email/verify';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['setGenres', 'showGenreForm']);
//        $this->middleware('guest:json')->except(['setGenres', 'showGenreForm']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'regex:/(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*]).*$/i', 'confirmed'],
//            'month' => ['required_with:year,day'],
//            'day' => ['required_with:month,year'],
//            'year' => ['required_with:month,day'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => [Rule::in(['M', 'F'])],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\User
     */
    protected function create(array $data)
    {
        $create = [
            'username' => substr($data['email'], 0, stripos($data['email'], '@')),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'birthday' => $data['birthday'] ? Carbon::createFromFormat('d.m.Y', $data['birthday'])->format('Y-m-d') : null,
        ];
        if (!empty($data['gender'])) {
            $create['gender'] = $data['gender'];
        }
        $user = User::create($create);
        $user->sendEmailVerificationNotification($user->email);

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);
        Auth::guard('json')->login($user);

        return $this->registered($request, $user)
            ?: redirect('/register-genres');
    }

    public function registerError(Request $request)
    {
        return view('auth.error');
    }

    public function showGenreForm()
    {
        $genres = Genre::all();

        return view('auth.registerGenres', ['genres' => $genres]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function setGenres(Request $request)
    {
        $genres = $request->all();
        if (null !== $genres['genres']) {
            $user = Auth::user();
            $user->favouriteGenres()->sync($genres['genres']);
        }

        return $this->registered($request, Auth::user())
            ?: redirect($this->redirectPath());
    }
}
