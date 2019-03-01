@extends('layouts.app')

@section('content')
    <div class="main__info">
        <div class="reg-page">{{$errors}}
            <h1 class="reg-page__title">{{ __('auth.readyToConnect') }} <img src="/images/logo.svg" alt="digico"> ?</h1>
            @include('auth.loginBySocial')
            <div class="reg-page__alternative text-with-line">
                <span>{{ __('auth.orRegisterWithEmail') }}</span>
            </div>
            <form method="POST" id="form-auth" action="{{ route('register') }}" class="reg-page__form">
                @csrf
                <span class="input-text email reg-page__line-input">
                <input id="email" name="email" type="email" required value="{{ old('email') }}">
                <label for="email">{{ __('auth.email') }}*</label>
            </span>
                <span class="input-text password reg-page__line-input">
                <input id="pass" name="password" type="password" required>
                <label for="pass">{{ __('auth.password') }}*</label>
            </span>
                <span class="input-text password reg-page__line-input">
                <input id="pass_two" type="password" name="password_confirmation" required>
                <label for="pass_two">{{ __('auth.confirmPassword') }}*</label>
            </span>
                <span class="reg-page__pass-error">{{ __('auth.passwordMismatch') }}</span>
                <div class="bd-block">
                    <span class="bd-block__title">{{ __('auth.birthday') }}</span>

                    <select class="custom-select wide bd-block__month" data-placeholder="{{ __('messages.month') }}" name="month">
                        <option value=""></option>
                        <option value="0">Январь</option>
                        <option value="1">Фeвраль</option>
                        <option value="2">Март</option>
                    </select>

                    <select class="custom-select bd-block__date" data-placeholder="{{ __('messages.day') }}" name="day">
                        <option value=""></option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                    </select>

                    <select class="custom-select bd-block__year" data-placeholder="{{ __('messages.year') }}"name="year">
                        <option value=""></option>
                        <option value="2000">2000</option>
                        <option value="2001">2001</option>
                        <option value="2002">2002</option>
                    </select>
                </div>
                <div class="reg-page__genders">
                <span class="input-radio genders-item">
                    <input name="gender" id="man" value="M" type="radio">
                    <label for="man">{{ __('auth.male') }}</label>
                </span>
                    <span class="input-radio genders-item">
                    <input name="gender" id="woman" value="F" type="radio">
                    <label for="woman">{{ __('auth.female') }}</label>
                </span>
                </div>
                <button type="submit" class="button active reg-page__submit">{{ __('auth.register') }}</button>
            </form>
            <p class="reg-page__intro">
            <span>
                {{ __('auth.pushButtonAccept') }} <a href="/">{{ __('auth.useConditions') }}</a>
            </span>
            </p>
            <p class="reg-page__enter">{{ __('auth.haveAccount') }}? <a href="{{ route('login') }}">{{ __('messages.signIn') }}</a> </p>
        </div>
    </div>
@endsection
