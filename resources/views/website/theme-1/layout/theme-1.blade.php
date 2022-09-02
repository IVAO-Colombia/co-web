<!DOCTYPE html>
<html lang="en">

@include('website.theme-1.layout.head')

<body data-icon="1">
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Header -->
        @include('website.theme-1.layout.header')
        <!-- end: Header -->
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
    <!--Pageloader plugin files-->
    <script src="{{ asset('theme-1/plugins/pageloader/pageloader.js') }}"></script>
    <script src="{{ asset('theme-1/plugins/pageloader/pageloader.init.js') }}"></script>
    @stack('scripts')
</body>

</html>
