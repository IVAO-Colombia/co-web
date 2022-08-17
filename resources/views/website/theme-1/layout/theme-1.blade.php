<!DOCTYPE html>
<html lang="en">

@include('website.theme-1.layout.head')

<body>
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
    <script src="js/jquery.js"></script>
    <script src="js/plugins.js"></script>
    <!--Template functions-->
    <script src="js/functions.js"></script>
    @stack('scripts')
</body>

</html>
