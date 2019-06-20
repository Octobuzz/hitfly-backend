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
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.newEventNotification.hello'), {{$user->username}}!</h3>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.newEventNotification.text')
                                    </p>
                                </td>
                            </tr>
                            @if(isset($events) && $events !== null)
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
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

@endsection
