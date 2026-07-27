<x-mail::message>
# {{ __('Your pilot slot reservation has been cancelled') }}

{{ __('Your reservation for :event was not confirmed in time and has been automatically cancelled and released to other pilots.', ['event' => $eventName]) }}

<x-mail::panel>
**{{ __('Flight') }}:** {{ $flightNumber }}<br>
**{{ __('Route') }}:** {{ $origin }} → {{ $destination }}<br>
**{{ __('Departure') }}:** {{ $departsAt->format('M j, Y H:i') }} UTC
</x-mail::panel>

{{ __('If the slot is still available, you may reserve it again from the event page.') }}

<x-mail::button :url="$reservationsUrl">
{{ __('View my reservations') }}
</x-mail::button>

{{ __('Thanks for flying with us!') }}<br>
{{ config('app.name') }}
</x-mail::message>
