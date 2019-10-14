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
                                        @if(isset($days) && $days!== null)
                                            @lang('emails.longAgoNotVisited.text',['count'=>$days])
                                        @else
                                            @lang('emails.longAgoNotVisited.textMonth')
                                        @endif
                                    </p>
                                </td>
                            </tr>
                            @if(isset($events) && $events !== null)
                            <tr>
                                <td style="padding-bottom: 20px;">
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.recommend')</h3>
                                    <p style="font-size: 12px; line-height: 14px; color: #606060; margin: 0;">
                                        @lang('emails.longAgoNotVisited.newEvents')
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                        <tbody>
                                        @foreach ($events as $event)
                                            @if ($loop->iteration%2 !== 0)
                                                <tr>
                                                    @endif
                                                    <td style="width: 50%;">
                                                        <a href="{{$event['url']}}" style="display: block; width: 247px; height: 115px; text-decoration: none; background-image: url('{{$event['img']}}');
                                                        @if ($loop->iteration%2 === 0)margin: 0 0 10px 5px;@else margin: 0 5px 10px 0; @endif">
                                                            <p style="width: 130px; font-size: 14px; font-weight: 700; color: #fff; margin: 0; padding: 10px;">{{$event['name']}}</p>
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
                @if( isset($importantEvents) && $importantEvents !== null)
                <tr>
                    <td>
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="padding-bottom: 40px;">
                            <tbody>
                            @foreach ($importantEvents as $impEvent)
                                @if ($loop->iteration%2 !== 0)
                                    <tr>
                                        @endif
                                        <td>
                                            <a href="{{$impEvent['url']}}" style="display: block; width: 600px; height: 272px; text-decoration: none; background-image: url('{{$impEvent['img']}}');">
                                                <p style="width: 500px; font-size: 24px; font-weight: 700; color: #fff; margin: 0; padding: 25px 50px 25px 50px;">{{$impEvent['name']}}</p>
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
                @if(isset($tracks) && $tracks !== null)
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

@endsection
