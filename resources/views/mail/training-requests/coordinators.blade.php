<x-mail::message>
# {{ __('New training request from :name', ['name' => $traineeName]) }}

{{ __('A new training request has been submitted and is waiting to be scheduled.') }}

<x-mail::panel>
**{{ __('Trainee') }}:** {{ $traineeName }} (VID {{ $traineeVid }})<br>
**{{ __('Email') }}:** {{ $traineeEmail }}<br>
**{{ __('Type') }}:** {{ $typeLabel }}<br>
**{{ __('Training') }}:** {{ $categoryLabel }}<br>
**{{ __('Requested on') }}:** {{ $requestedAt->format('M j, Y H:i') }} UTC
</x-mail::panel>

**{{ __('Observations') }}:**<br>
{{ $requestObservations }}

<x-mail::button :url="$showUrl">
{{ __('View training request') }}
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
