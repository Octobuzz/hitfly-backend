@extends('emails.email')

@section('content')

    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center">
                <tbody>
                <tr>
                    <td>
                        <a href="#" style="display: block; width: 600px; height: 230px; text-decoration: none; background-image: url('{{ env('APP_URL') }}/images/emails/img/thanks.png');">
                            <p style="font-size: 24px; font-weight: 700; line-height: 38px; text-align: center; text-shadow: 0 0 10px #8c8c8c; color: #fff; margin: 0; padding: 96px 0;">
                                @lang('emails.verifyEmail.hello')!
                            </p>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table width="504" cellpadding="0" cellspacing="0" border="0" align="center" style="padding: 35px 0;">
                            <tbody>
                            <tr>
                                <td>
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.verifyEmail.acceptRegister')</h3>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.verifyEmail.text',['link'=> $link])
                                    </p>
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
