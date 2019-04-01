@extends('emails.email')

@section('content')
    <h1>@lang('emails.register.thankForRegister')</h1>

    @lang('emails.register.hello')

    @forelse ($playLists as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['list_img'])<img src="{{$list['list_img']}}">@endif <h5>{{$list['name']}}</h5><span>{{$list['date']}}</span> {{$list['count_tracks']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.register.topEmpty')
    @endforelse

    @forelse ($topList as $track)
        <div>
            @if($track['link'])<a href="{{$track['link']}}">@endif
                <div>
                    @if($track['album_img'])<img src="{{$track['album_img']}}">@endif <h5>{{$track['track_name']}}</h5><span>{{$track['singer']}}</span> {{$track['track_time']}}
                </div>
            @if($track['link'])</a>@endif
        </div>
    @empty
        @lang('emails.register.topEmpty')
    @endforelse



    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
