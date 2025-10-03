@component('mail::message')
{{ trans_choice('mail.license_expiring_alert', $licenses->count(), ['count'=>$licenses->count(), 'threshold' => $threshold]) }}

<x-mail::table>

|        | {{ trans('mail.name') }} | {{ trans('mail.expires') }} | {{ trans('mail.terminates') }} |
| :------------- | :------------- | :------------- | :------------- |
@foreach ($licenses as $license)
| {{ ($license->expires_diff_for_humans <= ($threshold / 2)) ? '🚨' : (($license->expires_diff_for_humans <= $threshold) ? '⚠️' : ' ') }} | <a href="{{ route('licenses.show', $license->id) }}">{{ $license->name }}</a> | {{ $license->expires_formatted_date }} {!! $license->expires_diff_for_humans ? ' ('.$license->expires_diff_for_humans .')' : '' !!} | {{ $license->terminates_formatted_date }} {!! $license->terminates_diff_for_humans ? ' ('.$license->terminates_diff_for_humans .')' : '' !!} |
| <hr> | <hr> | <hr> | <hr> |
@endforeach
</x-mail::table>
@endcomponent
