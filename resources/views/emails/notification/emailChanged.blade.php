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
                                    <h3 style="font-size: 24px; font-weight: 700; color: #2f2f2f; margin: 0 0 15px;">@lang('emails.emailChanged.hello'), {{$user->username}}!</h3>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.emailChanged.text')
                                    </p>
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.emailChanged.newEmail') {{$user->email}}
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
