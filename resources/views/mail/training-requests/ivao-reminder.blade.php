<x-mail::message>
# {{ __('Reminder: request your training on the IVAO website') }}

{{ __('We still have your request for :category on file, but you must also open the request on the IVAO website in order to continue.', ['category' => $categoryLabel]) }}

<x-mail::button :url="$ivaoRequestUrl">
{{ __('Request on IVAO website') }}
</x-mail::button>

{{ __('Thanks for flying with us!') }}<br>
{{ config('app.name') }}
</x-mail::message>
