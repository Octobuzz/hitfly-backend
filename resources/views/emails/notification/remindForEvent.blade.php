@extends('emails.email')

@section('content')
    <h1>@lang('emails.remindForEvent.hello'), {{$user->username}}!</h1>
    @lang('emails.remindForEvent.text',['date'=>Date::parse($event['date'])->format('d F'),'name'=>$event['name']])<br>
    <h3>@lang('emails.remindForEvent.eventsUpcoming')</h3>
    @lang('emails.remindForEvent.newEvents')<br>

    @forelse ($eventsList as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['img'])<img src="{{$list['img']}}">@endif <h5>{{$list['name']}}</h5> {{$list['participant']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.remindForEvent.empty')
    @endforelse





    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
