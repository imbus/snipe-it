@component('mail::message')
# {{ trans('mail.hello') }} {{ $recipient->present()->fullName() }},

{{ $introduction }}:

@component('mail::table')
|        |          |
| ------------- | ------------- |
| **{{ trans('mail.user') }}** | {{ $assignedTo->present()->fullName() }} |
@if ((isset($item->name)) && ($item->name!=''))
| **{{ trans('mail.name') }}** | {{ $item->name }} |
@endif
@if (isset($item->asset_tag))
| **{{ trans('mail.asset_tag') }}** | {{ $item->asset_tag }} |
@endif
@if ($note != '')
| **{{ trans('mail.notes') }}** | {{ $note }} |
@endif
@endcomponent

{{ trans('mail.best_regards') }}

{{ $snipeSettings->site_name }}

@endcomponent
