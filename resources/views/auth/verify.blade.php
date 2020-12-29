@extends('layouts.app')

@section('content')
    <div class="main__info">
        <div class="forgot-pass">
            <h1 class="forgot-pass__title">{{__('auth.checkEmail')}}</h1>
            <p class="forgot-pass__text">
            @if (session('resent'))

                    {{__('auth.checkEmailLinkResend')}} <br>

            @endif
                {{__('auth.checkEmailText')}} <a href="{{ route('verification.resend') }}">{{__('auth.checkEmailLink')}}</a>.
            </p>
            <p class="forgot-pass__return">
                <a href="{{route('home')}}">{{__('auth.homeLink')}}</a>
            </p>
        </div>
    </div>
{{--<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>--}}
@endsection
