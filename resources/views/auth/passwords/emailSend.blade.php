@extends('layouts.app')

@section('content')
    <div class="main__info">
        <div class="forgot-pass">
            <h1 class="forgot-pass__title">{{__('passwords.checkEmail')}}</h1>
            <p class="forgot-pass__text">{{__('passwords.na')}} {{$email}} {{__('passwords.instruction')}}.</p>
            <p class="forgot-pass__return">
                <a href="/login">{{__('passwords.backToLogin')}}</a>
            </p>
        </div>
    </div>
@endsection
