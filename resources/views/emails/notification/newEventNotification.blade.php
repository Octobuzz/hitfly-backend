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
                            @if(empty($events) && $events !== null)
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
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

@endsection
