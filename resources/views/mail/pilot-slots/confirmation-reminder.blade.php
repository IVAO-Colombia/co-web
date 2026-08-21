<x-mail::message>
# {{ __('Confirm your pilot slot reservation') }}

{{ __('The event :event is starting soon and your pilot slot reservation is still pending confirmation.', ['event' => $eventName]) }}

<x-mail::panel>
**{{ __('Flight') }}:** {{ $flightNumber }}<br>
**{{ __('Route') }}:** {{ $origin }} → {{ $destination }}<br>
**{{ __('Departure') }}:** {{ $departsAt->format('M j, Y H:i') }} UTC
@if ($arrivesAt)
<br>**{{ __('Arrival') }}:** {{ $arrivesAt->format('M j, Y H:i') }} UTC
@endif
</x-mail::panel>

{{ __('Please confirm your reservation before the event starts, or it will be automatically cancelled and released to other pilots.') }}

<x-mail::button :url="$confirmationUrl">
{{ __('Confirm reservation') }}
</x-mail::button>

{{ __('Thanks for flying with us!') }}<br>
{{ config('app.name') }}
</x-mail::message>
