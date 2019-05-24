@extends('layouts.app')

@section('content')
    <div class="main__info">
        <div class="reg-page">
            <h1 class="reg-page__title">{{ __('auth.readyToConnect') }} <img src="/images/logo.svg" alt="digico"> ?</h1>
            @include('auth.loginBySocial')
            <div class="reg-page__alternative text-with-line">
                <span>{{ __('auth.orRegisterWithEmail') }}</span>
            </div>
            <form method="POST" id="form-auth" action="{{ route('register') }}" class="reg-page__form" novalidate>
                @csrf
                @isset($socialUser)
                    <div class="form-group row">
                        <div class="col-md-6">
                            <img class="img-circle img-bordered-sm" width=50 src="{{ $socialUser->avatar }}"
                                 alt="user image">
                        </div>
                    </div>
                @endisset

                <span class="input-text-wrapper reg-page__line-input {{ $errors->has('email') ? 'error' : '' }}">
                    <span class="input-text email">
                        <input id="email" name="email" type="email" required value="{{ old('email') }}">
                        <label for="email">{{ __('auth.email') }}*</label>
                    </span>
                    <span class="input-text-wrapper__error-msg">Введите корректный пароль</span>
                    @if ($errors->has('email'))
                        <span class="input-text-wrapper__error-msg">{{ $errors->first('email') }}</span>
                    @endif
                </span>

                <span class="input-text-wrapper reg-page__line-input {{ $errors->has('password') ? 'error' : '' }}">
                    <span class="input-text password">
                        <input id="pass" name="password" type="password" required>
                        <label for="pass">{{ __('auth.password') }}*</label>
                    </span>
                    <span class="input-text-wrapper__error-msg">Введите пароль</span>
                    @if ($errors->has('password'))
                        <span class="input-text-wrapper__error-msg">{{ $errors->first('password') }}</span>
                    @endif
                </span>

                <span class="input-text-wrapper reg-page__line-input">
                    <span class="input-text password ">
                        <input id="pass_two" type="password" name="password_confirmation" required>
                        <label for="pass_two">{{ __('auth.confirmPassword') }}*</label>
                    </span>
                    <span class="input-text-wrapper__error-msg">Пароли не совпадают</span>
                </span>

                <div class="bd-block">
                    <span class="bd-block__title">{{ __('auth.birthday') }}</span>
                    <span class="input-text-wrapper reg-page__line-input {{ $errors->has('birthday') ? 'error' : '' }}">
                        <span class="input-text js-datepicker date">
                            <input id="birthday" type="text" name="birthday" autocomplete="off" value="{{ $birthday ?? old('birthday') }}">
                            <label for="birthday">{{ __('auth.birthday') }}</label>
                        </span>
                        @if ($errors->has('birthday'))
                            <span class="input-text-wrapper__error-msg">{{ $errors->first('birthday') }}</span>
                        @endif
                    </span>
                </div>
                <div class="reg-page__genders">
                  <span class="input-radio genders-item">
                      <input name="gender" id="man" value="M" type="radio" {{old('gender')=='M'? 'checked':''}}>
                      <label for="man">{{ __('auth.male') }}</label>
                  </span>
                  <span class="input-radio genders-item">
                      <input name="gender" id="woman" value="F" type="radio" {{old('gender')=='F'? 'checked':''}}>
                      <label for="woman">{{ __('auth.female') }}</label>
                  </span>
                </div>
                <div class="reg-page__agreement input-text-wrapper">
                    <span class="input-checkbox">
                        <input id="tt" type="checkbox" name="agreement" required>
                        <label for="tt">Я согласен с <a href="javascript: void(0)">условиями использования</a> и <a href="javascript: void(0)">политикой конфиденциальности</a></label>
                    </span>
                </div>
                <button type="submit" class="button active reg-page__submit">{{ __('auth.register') }}</button>
            </form>
            <p class="reg-page__enter">{{ __('auth.haveAccount') }}? <a href="{{ route('login') }}">{{ __('messages.signIn') }}</a> </p>
        </div>
    </div>
@endsection
