<!DOCTYPE html>
<html lang="en">

@include('website.theme-1.layout.head')

<body data-icon="1">

    {{-- @include('website.theme-1.layout.email') --}}

    <x-jet-banner />
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Header -->
        @include('website.theme-1.layout.header')
        <!-- end: Header -->
        {{ $slot ?? null }}
        @yield('content')
        <!-- Footer -->
        @include('website.theme-1.layout.footer')
        <!-- end: Footer -->
    </div>
    <!-- end: Body Inner -->
    <!-- Scroll top -->
    <a id="scrollTop"><i class="icon-chevron-up"></i><i class="icon-chevron-up"></i></a>
    <!--Plugins-->
    <script src="{{ asset('theme-1/js/jquery.js') }}"></script>
    <script src="{{ asset('theme-1/js/plugins.js') }}"></script>
    <!--Template functions-->
    <script src="{{ asset('theme-1/js/functions.js') }}"></script>
    @livewireScripts
    <script>
        window.livewire.on('toast', function(data) {
            var content = {}
            content.message = data["message"];
            content.title = data["title"];
            content.icon = "fas fa-check";
            const type = data["type"] ?? "success";

            var notify = $.notify(content, {
                type: type,
                tymer: 0,
                template: '<div data-notify="container" class="bootstrap-notify col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                    '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                    '<span data-notify="icon"></span> ' +
                    '<span data-notify="title">{1}</span> ' +
                    '<span data-notify="message">{2}</span>' +
                    '<div class="progress" data-notify="progressbar">' +
                    '<div class="p-progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                    '</div>' +
                    '<a href="{3}" target="{4}" data-notify="url"></a>' +
                    '</div>',
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
