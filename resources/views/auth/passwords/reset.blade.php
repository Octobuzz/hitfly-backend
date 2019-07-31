@extends('layouts.app')

@section('content')
<div class="main__info">
    <div class="forgot-pass">
        <h1 class="forgot-pass__title">{{__('passwords.forgotPassword')}}?</h1>
        <p class="forgot-pass__text">{{__('passwords.enterEmail')}}</p>
        <form class="forgot-pass__form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <span class="input-text-wrapper forgot-pass__input {{ $errors->has('email') ? 'error' : '' }}">
                <span class="input-text email">
                    <input id="email" type="email"  name="email" value="{{ $email ?? old('email') }}"  required autofocus>
                    <label for="email">{{__('passwords.email')}}</label>
                </span>
                @if ($errors->has('email'))
                    <span class="input-text-wrapper__error-msg">{{ $errors->first('email') }}</span>
                @else
                    <span class="input-text-wrapper__error-msg">Неверный электронный адрес</span>
                @endif
            </span>

            <span class="input-text-wrapper forgot-pass__input">
                <span class="input-text password">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? 'error' : '' }}" name="password" required>
                    <label for="password">{{__('passwords.password')}}</label>
                </span>
                @if ($errors->has('password'))
                    <span class="input-text-wrapper__error-msg">{{ $errors->first('password') }}</span>
                @else
                    <span class="input-text-wrapper__error-msg">Введите корректный пароль</span>
                @endif
            </span>

            <span class="input-text-wrapper forgot-pass__input">
                <span class="input-text password">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    <label for="password-confirm">{{__('passwords.passwordConfirm')}}</label>
                </span>
                <span class="input-text-wrapper__error-msg"></span>
            </span>

            <button type="submit" class="button big active forgot-pass__submit">{{__('passwords.next')}}</button>
        </form>
        <p class="forgot-pass__return">
            <a href="{{ route('login') }}">{{__('passwords.backToLogin')}}</a>
        </p>
    </div>
</div>
@endsection
