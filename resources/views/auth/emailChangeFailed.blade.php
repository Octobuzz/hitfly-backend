@extends('layouts.app')

@section('content')
    <div class="main__info">
        <div class="forgot-pass">
            <p class="forgot-pass__text">
                {{__('auth.emailChangeFailed')}}
            </p>
            <p class="forgot-pass__return">
                <a href="/login">{{__('passwords.backToLogin')}}</a>
            </p>
        </div>
    </div>

@endsection
