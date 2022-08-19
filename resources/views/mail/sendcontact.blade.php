@component('mail::message')
    <!-- prettier-ignore-start -->
# Introduction

The body of your message.

@component('mail::button', ['url' => ''])
    Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
<!-- prettier-ignore-end -->
@endcomponent
