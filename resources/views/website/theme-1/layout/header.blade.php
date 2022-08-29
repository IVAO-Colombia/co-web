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
                            <a href="{{ route('ivao.login') }}" }}" class="btn btn-rounded btn-sm">Ingresar</a>
                        </li>

                        <li class="d-none d-xl-block d-lg-block mx-1">
                            <a href="{{ url('/') }}"
                                class="btn btn-rounded btn-outline btn-light btn-sm">Registrate</a>
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
            <!--Navigation-->
            <div id="mainMenu" class="">
                <div class="container">
                    <nav>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li class="dropdown"><a href="#">Piloto</a>
                                <ul class="dropdown-menu">
                                    <li><a target="_blank"
                                            href="https://wiki.ivao.aero/en/home/training/main/pilot_rank">Rangos</a>
                                    </li>
                                    <li><a target="_blank"
                                            href="http://www.aerocivil.gov.co/servicios-a-la-navegacion/servicio-de-informacion-aeronautica-ais">Cartas
                                            de Navegación</a>
                                    </li>
                                    <li><a target="_blank" href="https://www.ivao.aero/software/altitude">Altitude
                                            Software</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown"><a href="#">{{ __('ATC') }}</a>

                                <ul class="dropdown-menu">
                                    <li> <a target="_blank"
                                            href="https://wiki.ivao.aero/en/home/training/main/rank_atc">Rangos</a>
                                    </li>
                                    <li> <a href="./?page=fra">¿Qué aeropuerto puedo controlar?</a> </li>
                                    <li> <a href="./?page=gca">Guest Controller Approval</a> </li>
                                    <li> <a target="_blank"
                                            href="http://www.aerocivil.gov.co/servicios-a-la-navegacion/servicio-de-informacion-aeronautica-ais">Cartas
                                            de Navegación</a> </li>
                                    <li> <a target="_blank" href="https://www.ivao.aero/software/aurora">Aurora
                                            Software</a> </li>
                                </ul>


                            </li>
                            <li class="dropdown"><a href="#">{{ __('Traning') }}</a>
                                <ul class="dropdown-menu">

                                    <li><a href="#">{{ __('Charts') }}<span class="badge bg-danger">NEW</span></a></li>
                                    <li><a href="#">{{ __('Calendar') }}<span class="badge bg-danger">NEW</span></a></li>
                                    <li><a href="#">{{ __('General') }}</a>
                                    </li>

                                </ul>
                            </li>
                            <li><a href="http://co.forum.ivao.aero/" target="_blank">{{ __('Forum') }}</a>
                            </li>
                            @if (Auth::check())
                                <li class="dropdown">
                                    <a href="#">{{ Auth::user()->firstname }} - {{ Auth::user()->id }}</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#" }}">Perfil</a></li>
                                        <li><a href="#">Staff</a></li>
                                        <li>
                                            <a href="{{ route('ivao.logout') }}" }}">Logout</a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
            <!--end: Navigation-->
        </div>
    </div>
</header>
