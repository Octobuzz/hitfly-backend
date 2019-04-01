@extends('emails.email')

@section('content')
    <h1>@lang('emails.fewComments.hello'), {{$user->username}}!</h1>

    @lang('emails.fewComments.text')<br>
    <h3>@lang('emails.fewComments.newTracks')</h3>
    @lang('emails.birthday.playlistRecommend')<br>

    @forelse ($tracks as $track)
        <div>
            @if($track['link'])<a href="{{$track['link']}}">@endif
                <div>
                    @if($track['album_img'])<img src="{{$track['album_img']}}">@endif <h5>{{$track['track_name']}}</h5><span>{{$track['singer']}}</span> {{$track['track_time']}}
                </div>
                @if($track['link'])</a>@endif
        </div>
    @empty
        @lang('emails.fewComments.empty')
    @endforelse



    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
