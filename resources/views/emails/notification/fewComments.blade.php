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
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.fewComments.hello'), {{$user->username}}!</h3>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0 0 20px;">
                                        @lang('emails.fewComments.text')
                                    </p>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.fewComments.text2')
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="{{ env('APP_URL') }}/profile/reviews" style="display: block; width: 208px; height: 40px; font-size: 14px; line-height: 40px; text-decoration: none; text-align: center; color: #fff; background-image: url('{{ env('APP_URL') }}/images/emails/icons/gradient-link.png'); margin: 0 0 40px;">@lang('emails.fewComments.createFeedback')</a>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-bottom: 15px">
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 10px;">@lang('emails.fewComments.newTracks')</h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table width="504" cellpadding="0" cellspacing="0" border="0" align="center">
                                        <tbody>
                                        <tr>
                                            @forelse ($tracks as $track)
                                                <td style="width: 110px; vertical-align: text-bottom; padding-right: 22px; padding-bottom: 15px;">
                                                    @if($track['link'])<a href="{{$track['link']}}" style="display: block; text-decoration: none;">@endif
                                                        @if($track['album_img'])<img style="display: block; max-width: 100%; margin: 0 0 10px;" src="{{$track['album_img']}}">@endif
                                                        <p style="font-size: 12px; line-height: 14px; font-weight: 700; color: #231f20; margin: 0 0 5px;">{{$track['track_name']}}</p>
                                                        <p style="font-size: 12px; line-height: 14px; color: #231f20; margin: 0;">{{$track['singer']}}</p>
                                                    @if($track['link'])</a>@endif
                                                </td>

                                            @empty
                                                @lang('emails.fewComments.empty')
                                            @endforelse
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
