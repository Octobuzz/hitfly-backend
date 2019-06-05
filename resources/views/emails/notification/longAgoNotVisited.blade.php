@extends('emails.email')

@section('content')
    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 35px 0;">
                            <tbody>
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.longAgoNotVisited.hello'), {{$user->username}}</h3>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0 0 15px;">
                                        @lang('emails.longAgoNotVisited.text',['count'=>$days])
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.recommend')</h3>
                                    <p style="font-size: 12px; line-height: 14px; color: #606060; margin: 0;">
                                        @lang('emails.longAgoNotVisited.newEvents')
                                    </p>
                                </td>
                            </tr>
                            @if($events !== null)
                            <tr>
                                <td>
                                    <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                        <tbody>
                                        @foreach ($events as $event)
                                            @if ($loop->iteration%2 !== 0)
                                                <tr>
                                                    @endif
                                                    <td @if ($loop->iteration%2 === 0)align="right" @endif style="width: 50%;">
                                                        <a href="{{$event['url']}}" style="position: relative; display: block; text-decoration: none;">
                                                            <img style="display: block; max-width: 100%; margin: 0 0 10px;" src="{{$event['img']}}">
                                                            <p style="position: absolute; width: 130px; font-size: 14px; font-weight: 700; text-align: left; color: #fff; top: 10px; left: 10px; margin: 0;">{{$event['name']}}</p>
                                                        </a>
                                                    </td>
                                                    @if ($loop->iteration%2 === 0 || $loop->last)
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endif
                           {{-- @empty($recommendation)
                            <tr>
                                <td>
                                    <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                        <tbody>
                                        @foreach ($recommendation as $list)
                                            @if ($loop->iteration%2 !== 0)
                                                <tr>
                                                    @endif
                                                    <td @if ($loop->iteration%2 === 0)align="right" @endif style="width: 50%;">
                                                        <a href="{{$list['link']}}" style="display: block; max-width: 100%; margin: 0 0 10px;">
                                                            <img style="display: block; max-width: 100%; margin: 0 0 10px;" src="{{$list['list_img']}}">
                                                            <p style="position: absolute; width: 120px; font-size: 12px; line-height: 14px; font-weight: 700; text-align: right; color: #fff; top: 10px; right: 20px; margin: 0;">
                                                                {{$list['name']}}<br>
                                                                <span style="font-size: 10px; line-height: 14px; color: #fff;">{{$list['date']}}</span>
                                                            </p>
                                                            <p style="position: absolute; width: 120px; font-size: 10px; line-height: 14px; font-weight: 700; text-align: right; color: #fff; bottom: 20px; right: 20px; margin: 0;">
                                                                {{$list['count_tracks']}} трека
                                                            </p>
                                                        </a>
                                                    </td>
                                                    @if ($loop->iteration%2 === 0 || $loop->last)
                                                </tr>
                                            @endif
                                        @endforeach

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @endempty--}}
                            </tbody>
                        </table>
                    </td>
                </tr>
                @if($importantEvents !== null)
                <tr>
                    <td>
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-bottom: 40px;">
                            <tbody>
                            @foreach ($importantEvents as $impEvent)
                                @if ($loop->iteration%2 !== 0)
                                    <tr>
                                        @endif
                                        <td>
                                            <a href="{{$impEvent['url']}}" style="position: relative; display: block; text-decoration: none;">
                                                <img style="display: block; max-width: 100%;" src="{{$impEvent['img']}}">
                                                <p style="position: absolute; width: 330px; font-size: 24px; font-weight: 700; color: #fff; top: 25px; left: 50px; margin: 0;">{{$impEvent['name']}}</p>
                                            </a>
                                        </td>
                                        @if ($loop->iteration%2 === 0 || $loop->last)
                                    </tr>
                                @endif
                            @endforeach

                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
                @if($tracks !== null)
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                            <tbody>
                            <tr>
                                <td style="padding-bottom: 15px">
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 10px;">@lang('emails.longAgoNotVisited.newTracks')</h3>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-bottom: 40px;">
                            <tbody>
                            <tr>
                                @foreach ($tracks as $track)
                                    <td style="width: 110px; vertical-align: text-bottom; padding-right: 22px; padding-bottom: 15px;">
                                        @if($track['link'])<a href="{{$track['link']}}" style="display: block; text-decoration: none;">@endif
                                            @if($track['album_img'])<img style="display: block; max-width: 100%; margin: 0 0 10px;" src="{{$track['album_img']}}">@endif
                                            <p style="font-size: 12px; line-height: 14px; font-weight: 700; color: #231f20; margin: 0 0 5px;">{{$track['track_name']}}</p>
                                            <p style="font-size: 12px; line-height: 14px; color: #231f20; margin: 0;">{{$track['singer']}}</p>
                                        @if($track['link'])</a>@endif
                                    </td>
                                @endforeach
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                @endif
                </tbody>
            </table>
        </td>
    </tr>
    {{--<h1>@lang('emails.longAgoNotVisited.hello'), {{$user->username}}!</h1>

    @lang('emails.longAgoNotVisited.text',['count'=>$days])<br>
    <h3>@lang('emails.longAgoNotVisited.eventsUpcoming')</h3>
    @lang('emails.longAgoNotVisited.newEvents')<br>

    @forelse ($events as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['img'])<img src="{{$list['img']}}">@endif <h5>{{$list['name']}}</h5> {{$list['participant']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.longAgoNotVisited.empty')
    @endforelse

    <h3>@lang('emails.recommend')</h3>
    @lang('emails.longAgoNotVisited.playlistRecommend')<br>

    @forelse ($recommendation as $list)
        <div>
            @if($list['link'])<a href="{{$list['link']}}">@endif
                <div>
                    @if($list['list_img'])<img src="{{$list['list_img']}}">@endif <h5>{{$list['name']}}</h5><span>{{$list['date']}}</span> {{$list['count_tracks']}}
                </div>
                @if($list['link'])</a>@endif
        </div>
    @empty
        @lang('emails.longAgoNotVisited.empty')
    @endforelse

    <h3>@lang('emails.longAgoNotVisited.top')</h3>
    @lang('emails.longAgoNotVisited.rating')<br>
    @forelse ($tracks as $track)
        <div>
            @if($track['link'])<a href="{{$track['link']}}">@endif
                <div>
                    @if($track['album_img'])<img src="{{$track['album_img']}}">@endif <h5>{{$track['track_name']}}</h5><span>{{$track['singer']}}</span> {{$track['track_time']}}
                </div>
                @if($track['link'])</a>@endif
        </div>
    @empty
        @lang('emails.longAgoNotVisited.empty')
    @endforelse



    @lang('emails.regards'),
    {{ config('app.name') }} --}}
@endsection
