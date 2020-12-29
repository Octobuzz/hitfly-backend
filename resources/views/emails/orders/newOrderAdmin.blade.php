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
                                    <p style="font-size: 16px; line-height: 24px; color: #313131; margin: 0;">
                                        @lang('emails.newOrderAdmin.text',['product'=> $order->description, 'name'=> $buyer->username,'email'=> $buyer->email,'id'=> $buyer->id])
                                    </p>
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
