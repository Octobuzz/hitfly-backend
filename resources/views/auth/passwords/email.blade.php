@extends('layouts.app')

@section('content')
<div class="main__info">
    <div class="forgot-pass">
        <h1 class="forgot-pass__title">{{__('passwords.forgotPassword')}}?</h1>
        <p class="forgot-pass__text">{{__('passwords.enterEmail')}}</p>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form class="forgot-pass__form" method="POST" action="{{ route('password.email') }}">
            @csrf
            <span class="input-text email forgot-pass__input">
        <input id="email" type="email"  name="email" value="{{ $email ?? old('email') }}" {{ $errors->has('email') ? ' class=error' : '' }} required autofocus>
        <label for="email">{{__('passwords.email')}}</label>
    </span>


            <button type="submit" class="button big active forgot-pass__submit">{{__('passwords.next')}}</button>
        </form>
        <p class="forgot-pass__return">
            <a href="{{ route('login') }}">{{__('passwords.backToLogin')}}</a>
        </p>
    </div>
</div>
@endsection
