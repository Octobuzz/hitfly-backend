@extends('emails.email')

@section('content')
    <h1>@lang('emails.emailChanged.hello'), {{$user->username}}!</h1>

    @lang('emails.emailChanged.text')<br>
    @lang('emails.emailChanged.newEmail') {{$user->email}}
    <br>




    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
