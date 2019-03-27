@extends('emails.email')

@section('content')
    <h1>@lang('emails.monthDispatchNotVisited.hello'), {{$user->username}}!</h1>

    @lang('emails.monthDispatchNotVisited.text',['month'=>Date::now()->format('F')])<br>
    <h3>@lang('emails.monthDispatchNotVisited.eventsUpcoming')</h3>
    @lang('emails.monthDispatchNotVisited.newEvents')<br>

    @forelse ($events as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['img'])<img src="{{$list['img']}}">@endif <h5>{{$list['name']}}</h5> {{$list['participant']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.monthDispatchNotVisited.empty')
    @endforelse

    <h3>@lang('emails.recommend')</h3>
    @lang('emails.monthDispatchNotVisited.playlistRecommend')<br>

    @forelse ($recommendation as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['list_img'])<img src="{{$list['list_img']}}">@endif <h5>{{$list['name']}}</h5><span>{{$list['date']}}</span> {{$list['count_tracks']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.monthDispatchNotVisited.empty')
    @endforelse

    <h3>@lang('emails.monthDispatchNotVisited.top')</h3>
    @lang('emails.monthDispatchNotVisited.rating')<br>
    @forelse ($tracks as $track)
        <div>
            @if($track['link'])<a href="{{$track['link']}}">@endif
                <div>
                    @if($track['album_img'])<img src="{{$track['album_img']}}">@endif <h5>{{$track['track_name']}}</h5><span>{{$track['singer']}}</span> {{$track['track_time']}}
                </div>
                @if($track['link'])</a>@endif
        </div>
    @empty
        @lang('emails.monthDispatchNotVisited.empty')
    @endforelse



    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
