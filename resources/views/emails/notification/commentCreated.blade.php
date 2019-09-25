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
                                <td>
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.commentCreated.hello'), {{$username}}!</h3>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.commentCreated.text', ['type'=>$type,'commentator'=>$commentator->username,'name'=>$commentableName,'link'=>$link])
                                    </p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                {{--<tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 0 0 35px;">
                            <tbody>
                            <tr>
                                <td style="width: 80px; vertical-align: top;">
                                    <img style="display: block; max-width: 100%;" src="{{$commentableImageUrl}}">
                                </td>
                                <td>
                                    <p style="font-size: 16px; font-weight: 700; color: #231f20; margin: 0 0 15px;">{{$commentableName}}</p>
                                    <p style="font-size: 14px; color: #606060; margin: 0 0 15px;">{{$commentableAuthor}}</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 0 0 35px;">
                            <tbody>
                            <tr>
                                <td style="width: 80px; vertical-align: top;">
                                    <img style="display: block; max-width: 100%;" src="{{$commentatorAvatar}}">
                                </td>
                                <td style="vertical-align: top;">
                                    <p style="font-size: 16px; font-weight: 700; color: #231f20; margin: 0 0 15px;">{{$commentator->username}}</p>
                                    <p style="font-size: 14px; line-height: 20px; color: #606060; margin: 0 0 15px;">
                                        {{$comment}}
                                    </p>
                                </td>
                                <td style=" vertical-align: top;">
                                    <p style="font-size: 12px; text-align: right; color: #a6a6a6; margin: 0 0 15px;">Сейчас</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 0 0 35px;">
                            <tbody>
                            <tr>
                                <td>
                                    <a href="{{$allCommentsUrl}}" style="display: block; width: 255px; height: 40px; font-size: 14px; line-height: 40px; text-decoration: none; text-align: center; color: #fff; background-image: url('{{ env('APP_URL') }}/images/emails/icons/gradient-link-big.png');">
                                        @lang('emails.commentCreated.seeAllComments')
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>--}}
                </tbody>
            </table>
        </td>
    </tr>
@endsection
