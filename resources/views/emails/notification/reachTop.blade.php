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
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.reachTop.hello',['top'=>$topCount])!</h3>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.reachTop.text',['link'=>$track['link'],'name'=>$track['track_name'],'position'=>$track['position'],'top'=>$topCount])

                                    </p>
                                </td>
                            </tr>
                            {{--<tr>
                                <td>
                                    <a href="{{$topUrl}}" style="display: block; width: 208px; height: 40px; font-size: 14px; line-height: 40px; text-decoration: none; text-align: center; color: #fff; background-image: url('{{ config('app.url') }}/images/emails/icons/gradient-link.png');">ТОП-{{$topCount}}</a>
                                </td>
                            </tr>--}}
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>

@endsection
