<x-mail::message>
# {{ __('Your training request has been submitted') }}

{{ __('We have received your request for :category. Our training staff will review it and get in touch with you.', ['category' => $categoryLabel]) }}

<x-mail::panel>
{{ __('You must also request this training on the IVAO website. Submitting this form alone is not enough.') }}
</x-mail::panel>

<x-mail::button :url="$ivaoRequestUrl">
{{ __('Request on IVAO website') }}
</x-mail::button>

{{ __('You can track the status of your request from your dashboard.') }}

<x-mail::button :url="$trainingsUrl">
{{ __('View my training requests') }}
</x-mail::button>

{{ __('Thanks for flying with us!') }}<br>
{{ config('app.name') }}
</x-mail::message>
