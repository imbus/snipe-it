@component('mail::message')
# {{ trans('mail.hello') }} {{ $recipient->present()->fullName() }},

{{ trans('mail.best_regards') }}

{{ $snipeSettings->site_name }}

@endcomponent
