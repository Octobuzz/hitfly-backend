@extends('emails.email')

@section('content')
    <h1>@lang('emails.reachTop.hello'), {{$track['user']->username}}!</h1>
    @lang('emails.reachTop.text',['link'=>'<a href="'.$track['link'].'" >ссылке</a>','name'=>$track['track_name'],'position'=>$track['position'],'top'=>$topCount])<br>
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
        @lang('emails.reachTop.empty')
    @endforelse
    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
