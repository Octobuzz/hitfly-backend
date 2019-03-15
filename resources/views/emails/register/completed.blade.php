@component('mail::message')
    # @lang('emails.register.thankForRegister')

    @lang('emails.register.hello')


    @lang('emails.regards'),
    {{ config('app.name') }}
@endcomponent
