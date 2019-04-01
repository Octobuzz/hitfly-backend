@component('mail::message')
# @lang('emails.resetPassword.passwordChanged')

@lang('emails.resetPassword.passwordChangedSuccess')

<br>
@lang('emails.regards'),<br>
{{ config('app.name') }}
@endcomponent
