@extends('layouts.app')

@section('content')

<div class="reg-page">
    <h1 class="reg-page__title">{{__('auth.readyToComeBack')}} <img src="/images/logo.svg" alt="digico"> ?</h1>
    @include('auth.loginBySocial')
    <div class="reg-page__alternative text-with-line">
        <span>{{__('auth.signUpWithEmail')}}</span>
    </div>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form action="{{ route('login') }}" method="POST" class="reg-page__form">
        @csrf
        <span class="input-text-wrapper reg-page__line-input {{ $errors->has('email') ? 'error' : '' }}">
            <span class="input-text email">
                <input id="email" name="email" type="email" required>
                <label for="email">{{__('auth.email')}}</label>
            </span>
            @if ($errors->has('email'))
                <span class="input-text-wrapper__error-msg" role="alert">{{ $errors->first('email') }}</span>
            @endif
        </span>

        <span class="input-text-wrapper reg-page__line-input {{ $errors->has('password') ? 'error' : '' }}">
            <span class="input-text password ">
                <input id="pass" name="password" type="password" required>
                <label for="pass">{{__('auth.password')}}</label>
            </span>
            @if ($errors->has('password'))
                <span class="input-text-wrapper__error-msg">{{ $errors->first('password') }}</span>
            @endif
        </span>

        <div class="auth-page__save">
            <span class="input-checkbox">
                <input id="tt" type="checkbox" name="remember">
                <label for="tt">{{__('auth.rememberMe')}}</label>
            </span>
            @if (Route::has('password.request'))
            <a class="auth-page__save-link" href="{{ route('password.request') }}">{{__('auth.forgotPassword')}}?</a>
            @endif
        </div>

        <button type="submit" class="button active reg-page__submit">{{__('messages.signIn')}}</button>
    </form>
    <div class="auth-page__bottom">
        <p class="auth-page__register">{{__('auth.notRegistered')}}?</p>
        <a class="auth-page__register-link button gradient big" href="{{ route('register') }}">{{__('auth.register')}}</a>
    </div>
</div>
@endsection
