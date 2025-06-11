@component('mail::message')
# {{ trans('mail.hello') }} {{ $recipient->present()->fullName() }},

{fullName} {accepted|declined} the following checkout:

@component('mail::table')
|        |          |
| ------------- | ------------- |
@if ((isset($item->name)) && ($item->name!=''))
    | **{{ trans('mail.name') }}** | {{ $item->name }} |
@endif
@if (isset($item->asset_tag))
    | **{{ trans('mail.asset_tag') }}** | {{ $item->asset_tag }} |
@endif
@if (isset($note) && $note != '')
    | **{{ trans('mail.notes') }}** | {{ $note }} |
@endif
@endcomponent

{{ trans('mail.best_regards') }}

{{ $snipeSettings->site_name }}

@endcomponent
