<input x-data x-ref="input" x-init="new Pikaday({ field: $refs.input })" type="text" {{ $attributes }}>

@once('styles')
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
@endonce
