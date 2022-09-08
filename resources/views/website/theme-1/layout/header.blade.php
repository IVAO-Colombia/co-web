<header id="header" data-transparent="true" data-fullwidth="true" class="dark">
    <div class="header-inner">
        <div class="container">
            <!--Logo-->
            <div id="logo">
                <a href="{{ url('/') }}">


                    <img src="{{ asset('img/Logo1_WHITE.png') }}" class="logo-default">
                    <img src="{{ asset('img/Logo1_WHITE.png') }}" class="logo-dark">
                </a>
            </div>
            <!--End: Logo-->

            <!--Header Extras-->
            <div class="header-extras">
                <ul>
                    @if (!Auth::check())
                        <li class="d-none d-xl-block d-lg-block mx-1">
                            <a href="{{ route('ivao.login') }}" }}"
                                class="btn btn-rounded btn-sm">{{ __('Login') }}</a>
                        </li>

                        <li class="d-none d-xl-block d-lg-block mx-1">
                            <a href="https://ivao.aero/register" target="_blank"
                                class="btn btn-rounded btn-outline btn-light btn-sm">{{ __('Sign Up') }}</a>
                        </li>
                    @endif

                    <li>
                        <div class="p-dropdown">
                            <a href="#">
                                <i class="icon-globe"></i><span>{{ Str::upper(App::currentLocale()) }}</span></a>
                            <ul class="p-dropdown-content">
                                <li><a href="{{ url('locale/es') }}">{{ __('general.spanish') }}</a></li>
                                <li><a href="{{ url('locale/en') }}">{{ __('general.english') }}</a></li>
                            </ul>
                        </div>
                    </li>



                </ul>
            </div>
            <!--end: Header Extras-->
            <!--Navigation Resposnive Trigger-->
            <div id="mainMenu-trigger">
                <a class="lines-button x"><span class="lines"></span></a>
            </div>
            <!--end: Navigation Resposnive Trigger-->
            @include('website.theme-1.layout.nav')
        </div>
    </div>
</header>
