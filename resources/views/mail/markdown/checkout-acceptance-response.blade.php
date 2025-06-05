@component('mail::message')
# {{ trans('mail.hello') }} {{ $target->present()->fullName() }},

{{ trans('mail.best_regards') }}

{{ $snipeSettings->site_name }}

@endcomponent
