@extends('emails.email')

@section('content')
    <h1>@lang('emails.emailChange.hello'), {{$user->username}}!</h1>

    @lang('emails.emailChange.text')<br>

    {{$email}}
    <br>
    <a href="{{$url}}">{{$url}}</a>



    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
