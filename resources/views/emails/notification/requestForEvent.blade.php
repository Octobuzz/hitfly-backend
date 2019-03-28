@extends('emails.email')

@section('content')
    <h1>@lang('emails.requestForEvent.hello'), {{$user->username}}!</h1>
    @lang('emails.requestForEvent.text',['link'=>'<a href="'.$event['link'].'" >ссылке</a>','name'=>$event['name']])<br>
    <h3>@lang('emails.requestForEvent.eventsUpcoming')</h3>
    @lang('emails.requestForEvent.newEvents')<br>

    @forelse ($eventsList as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['img'])<img src="{{$list['img']}}">@endif <h5>{{$list['name']}}</h5> {{$list['participant']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.requestForEvent.empty')
    @endforelse





    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
