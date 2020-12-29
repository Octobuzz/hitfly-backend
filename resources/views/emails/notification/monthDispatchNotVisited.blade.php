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
                                    <h1>@lang('emails.monthDispatchNotVisited.hello'), {{$user->username}}!</h1>

                                    @lang('emails.monthDispatchNotVisited.text',['month'=>\App\Helpers\DateHelpers::getNameMonthInFuture(Date::now()->format('F'))])<br>

                                    @if(false === empty($events) && $events !== null)
                                        <h3>@lang('emails.monthDispatchNotVisited.eventsUpcoming')</h3>
                                        @lang('emails.monthDispatchNotVisited.newEvents')<br>
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
                                    @endif
                                </td>
                            </tr>
                            @if(false === empty($recommendation) && $recommendation !== null)

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

                            {{--<tr>
                                <td style="padding-bottom: 20px;">
                                    <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                        <tbody>
                                    <tr>
                                        <td style="padding-bottom: 20px;">
                                            <h3>@lang('emails.recommend')</h3>
                                            @lang('emails.monthDispatchNotVisited.playlistRecommend')<br>
                                        </td>
                                    </tr>


                                    @if(!empty($recommendation))
                                        @foreach ($recommendation as $list)

                                                @if($list['link'])<a href="{{$list['link']}}" style="display: block; width: 160px; height: 160px; text-decoration: none; @if($list['list_img'])background-image: url('{{$list['list_img']}}');@endif margin-right: 5px;">@endif
                                                    <p style="width: 140px; height: 120px; font-size: 12px; line-height: 14px; font-weight: 700; text-align: right; color: #fff; margin: 0; padding: 10px;">
                                                        {{$list['name']}}<br>
                                                        <span style="font-size: 10px; line-height: 14px; color: #fff;">{{$list['date']}}</span>
                                                    </p>
                                                    <p style="width: 140px; font-size: 10px; line-height: 14px; font-weight: 700; text-align: right; color: #fff; margin: 0; padding: 0 10px 0 10px;">
                                                        {{$list['count_tracks']}}
                                                    </p>
                                                    @if($list['link'])</a>@endif

                                        @endforeach
                                    @endif
                                </td>
                            </tr>
                                        </tbody>
                                    </table>
                                --}}
                            @endif
                            <tr>
                                <td>
                                    <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                        <tbody>
                                        <tr>
                                            <td style="padding-bottom: 20px;">
                                                <h3>@lang('emails.monthDispatchNotVisited.top')</h3>
                                                @lang('emails.monthDispatchNotVisited.rating')<br>
                                            </td>
                                        </tr>
                                        <tr>
                                            @if(!empty($tracks))
                                                @foreach ($tracks as $track)
                                                    <td style="width: 110px; vertical-align: text-bottom; padding-right: 22px; padding-bottom: 15px;">
                                                        @if($track['link'])<a href="{{$track['link']}}" style="display: block; text-decoration: none;">@endif
                                                            @if($track->getImageUrl())<img style="display: block; max-width: 100%; margin: 0 0 10px;" src="{{config('app.url').$track->getImageUrl()}}">@endif
                                                            <p style="font-size: 12px; line-height: 14px; font-weight: 700; color: #231f20; margin: 0 0 5px;">{{$track['track_name']}}</p>
                                                            <p style="font-size: 12px; line-height: 14px; color: #231f20; margin: 0;">{{$track['singer']}}</p>
                                                            @if($track['link'])</a>@endif
                                                    </td>
                                                @endforeach
                                            @endif
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

@endsection
