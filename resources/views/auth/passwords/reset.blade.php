@extends('layouts.app')

@section('content')
<div class="main__info">
    <div class="forgot-pass">
        <h1 class="forgot-pass__title">{{__('passwords.forgotPassword')}}?</h1>
        <p class="forgot-pass__text">{{__('passwords.enterEmail')}}</p>
        <form class="forgot-pass__form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
        <span class="input-text email forgot-pass__input {{ $errors->has('email') ? 'error' : '' }}">
            <input id="email" type="email"  name="email" value="{{ $email ?? old('email') }}"  required autofocus>
            <label for="email">{{__('passwords.email')}}</label>
        </span>

        <span class="input-text password forgot-pass__input">
            <input id="password" type="password" class="form-control{{ $errors->has('password') ? 'error' : '' }}" name="password" required>
            <label for="password">{{__('passwords.password')}}</label>
        </span>
        <span class="input-text password forgot-pass__input">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            <label for="password">{{__('passwords.passwordConfirm')}}</label>
        </span>
            <button type="submit" class="button big active forgot-pass__submit">{{__('passwords.next')}}</button>
        </form>
        <p class="forgot-pass__return">
            <a href="{{ route('login') }}">{{__('passwords.backToLogin')}}</a>
        </p>
    </div>
</div>
@endsection
