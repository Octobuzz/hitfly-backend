@extends('emails.email')

@section('content')
    <h1>@lang('emails.birthday.hello'), {{$user->name}}!</h1>

    @lang('emails.birthday.text')<br>
    <h3>@lang('emails.recommend')</h3>
    @lang('emails.birthday.playlistRecommend')<br>

    @forelse ($playLists as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['list_img'])<img src="{{$list['list_img']}}">@endif <h5>{{$list['name']}}</h5><span>{{$list['date']}}</span> {{$list['count_tracks']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.birthday.topEmpty')
    @endforelse



    @lang('emails.regards'),
    {{ config('app.name') }}
@endsection
