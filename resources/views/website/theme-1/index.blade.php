@extends('website.theme-1.layout.theme-1')
@section('content')
    @php
        $aleatorio = (bool) random_int(0, 1);
    @endphp
    @auth
        <!-- Inspiro Slider -->
        <div id="slider" class="inspiro-slider slider-fullscreen">
            <!-- Slide 1 -->
            <div class="slide" data-bg-video="{{ asset('video/video-1.mp4') }}">
                <div class="bg-overlay"></div>
                {{-- <div class="shape-divider" data-style="1" data-height="300"></div> --}}
                <div class="container">
                    <div class="slide-captions text-start text-light">
                        <!-- Captions -->
                        <h2 class="text-uppercase text-lg ">{{ __('Welcome') }}
                            {{ auth()->user()->firstname }}
                        </h2>
                        <p class="fw-light">¡Despega tu pasión en los cielos colombianos!
                            <br>
                            Somos
                            <b>
                                <span class="text-rotator">Los Mejores Pilotos,Los Mejores Controladores, La Mejor
                                    División</span>
                            </b>



                        </p>

                        <!-- end: Captions -->
                    </div>
                </div>
            </div>
            <!-- end: Slide 1 -->
            <a href="#events" id="scroller-icon"><span></span></a>
        </div>
        <!--end: Inspiro Slider -->
    @else
        @include('website.theme-1.slider')
    @endauth





    <!-- BLOG -->
    <section class="content" id="events">
        <div class="container">
            <div class="heading-text heading-section">
                <h2 class="">{{ __('Events') }}</h2>
                <span class="lead">{{ __('Our next events.') }}</span>
            </div>
            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                @foreach ($featuredEvents as $item)
                    <!-- Post item-->
                    <div class="post-item border">
                        <div class="post-item-wrap">
                            <div class="post-image">
                                <a href="{{ route('front.event_detail', $item->slug) }}">
                                    <img alt="{{ $item->title }}" src="{{ asset('storage/events/' . $item->image) }}">
                                </a>
                                @if (false)
                                    <span class="post-meta-category">
                                        <a href="">Lifestyle</a>
                                    </span>
                                @endif
                            </div>
                            <div class="post-item-description">
                                <span class="post-meta-date"><i class="fa fa-calendar-o"></i>{{ $item->date_time }}</span>
                                <span class="post-meta-comments"><a href=""><i
                                            class="fa fa-comments-o"></i></a></span>
                                <h2>
                                    <a href="{{ route('front.event_detail', $item->slug) }}">{{ $item->title }}
                                    </a>
                                </h2>
                                <p>{!! \Illuminate\Support\Str::limit(strip_tags($item->description), 200) !!}</p>
                                <a href="{{ route('front.event_detail', $item->slug) }}" class='item-link'>Leer Más <i
                                        class="icon-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- end: Post item-->
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                <a href="#" class="btn btn-light btn-lg">Ver Más Eventos</a>
            </div>
        </div>
    </section>
    <!-- end: BLOG -->

    <!-- ATC -->
    <section>
        <div class="bg-overlay"></div>
        <div class="shape-divider" data-style="8"></div>
        <div class="container">
            <div class="heading-text heading-section">
                <h2 class="">{{ __('Controllers in Colombia') }}</h2>
                <span class="lead">{{ __('Here you can see the online control in Colombia') }}</span>
            </div>
            <div class="row">

                @if (count($flights['atc']) > 0)
                    <div class="grid-layout grid-3-columns" data-item="grid-item" data-margin="10">
                        @foreach ($flights['atc'] as $item)
                            <div class="grid-item">

                                <!-- Widget My Cart -->
                                <div class="widget border-box p-cb" style="cursor: initial">
                                    <div class="boxed bg--light ">
                                        {{-- {{ dd($item) }} --}}
                                        <div class="row">
                                            <div class="col-md-5 text-center">
                                                <i class="icon icon--sm block fas fa-broadcast-tower"
                                                    style="color: gray; font-size:30px;"></i>
                                                {{ $item->atcSession->frequency }} Mhz <br>

                                                {{ Carbon\Carbon::parse($item->time)->format('H:i') }}

                                            </div>
                                            <div class="col-md-7">
                                                @php
                                                    $icao = mb_substr($item->callsign, 0, 4);

                                                @endphp
                                                <p class="fw-light mb-0" style="min-height: 95px;">
                                                    <a href="https://webeye.ivao.aero/?atcId={{ $item->id }}"
                                                        target="_blank" class="color--primary"
                                                        title="Webeye"><b>{{ $item->atcSession->position }} </b> -
                                                        {{ $icao }}</a>
                                                    <br>


                                                    <a href="https://www.ivao.aero/member?id={{ $item->userId }}"
                                                        target="_blank" class="color--primary"
                                                        title="Ver perfil controlador">
                                                        {{ $item->userId }}
                                                    </a>
                                                    <br>
                                                    <img src="https://ivao.aero/data/images/ratings/atc/{{ $item->rating }}.gif"
                                                        alt="">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end: Widget My Cart -->
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="col-md-6">
                        <div class="alert">
                            <div class="alert__body">
                                <span class="color--dark"> Ningun controlador conectado</span>
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- end: ATC -->

    <!-- VUELOS -->
    <section>
        {{-- data-bg-image="{{ asset('img/sksm-exterior.jpg') }}" --}}
        {{-- <div class="bg-overlay" data-style="4"></div> --}}
        {{-- <div class="shape-divider" data-style="1"></div> --}}
        {{-- <div class="shape-divider" data-style="18" data-position="top" data-flip-vertical="true"></div> --}}
        <div class="container ">
            <div class="heading-text heading-section">
                <h2 class="">{{ __('Live Flights') }}</h2>
                <span class="lead">{{ __('Here you will see all IVAO flights to and from Colombia') }}</span>
            </div>

            <div class="row justify-content-center" style="background-color: white">
                <div class="col-md-12 text-center boxed boxed--border bg--white">

                    <div class="mt-4">
                        <div class="tabs">
                            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="arrival-tab" data-bs-toggle="tab" href="#arrival"
                                        role="tab" aria-controls="arrival" aria-selected="true"> <i
                                            class="icon icon--sm block fas fa-plane-arrival"></i>
                                        <span class="h5">{{ __('Arrivals') }}</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="departure-tab" data-bs-toggle="tab" href="#departure"
                                        role="tab" aria-controls="departure" aria-selected="false">
                                        <i class="icon icon--sm block fas fa-plane-departure"></i>
                                        <span class="h5">{{ __('Departures') }}</span>
                                    </a>
                                </li>


                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="arrival" role="tabpanel"
                                    aria-labelledby="arrival-tab">
                                    <div class="table-responsive">
                                        <table class="table border--round table--alternate-row">
                                            <thead>
                                                <tr>
                                                    <th>ETA</th>
                                                    <th>Aerolinea</th>
                                                    <th>Callsign</th>
                                                    <th>Origen</th>
                                                    <th>Destino</th>
                                                    <th>Aeronave</th>
                                                    <th>Estado</th>
                                                    <th>Información</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @if (count($flights['arrival']) > 0)
                                                    @foreach ($flights['arrival'] as $item)
                                                        @php

                                                            // $eet = \Carbon\Carbon::parse($item->flightPlan->eet);
                                                            $timeDeparture = \Carbon\Carbon::parse($item->flightPlan->departureTime);
                                                            $arrivaltime = $timeDeparture->addSeconds($item->flightPlan->eet);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $arrivaltime->format('H:i\z') }}</td>
                                                            <td>

                                                                @php
                                                                    $icao1L = mb_substr($item->callsign, 0, 1);
                                                                    $icao3L = mb_substr($item->callsign, 0, 3);
                                                                    $icao2L = mb_substr($item->callsign, 0, 2);
                                                                    if ($icao1L == 'N' && $icao3L !== 'NSE' && $icao3L !== 'NKS') {
                                                                        $airline = $icao1L;
                                                                    } elseif ($icao2L == 'HK') {
                                                                        $airline = $icao2L;
                                                                    } else {
                                                                        $airline = $icao3L;
                                                                    }
                                                                @endphp
                                                                @if (File::exists(public_path("logos-icao/$airline.png")))
                                                                    <img class="airlineslogo"
                                                                        src="{{ asset("logos-icao/$airline.png") }}" />
                                                                @endif
                                                            </td>
                                                            <td><a href="https://www.ivao.aero/member?id={{ $item->userId }}"
                                                                    target="_blank"
                                                                    title="Ver perfil del piloto">{{ $item->callsign }}</a>
                                                            </td>

                                                            <td>{{ $item->flightPlan->departureId }}</td>
                                                            <td>{{ $item->flightPlan->arrivalId }}</td>
                                                            <td>{{ $item->flightPlan->aircraft->icaoCode }}</td>
                                                            <td>{{ __($item->lastTrack->state) }}</td>
                                                            <td><a href="{{ route('front.fasttrack', $item->callsign) }}"
                                                                    class=""> Ver</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8">No hay vuelos</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="departure" role="tabpanel"
                                    aria-labelledby="departure-tab">
                                    <div class="table-responsive">
                                        <table class="table border--round table--alternate-row">
                                            <thead>
                                                <tr>
                                                    <th>ETOD</th>
                                                    <th>Aerolinea</th>
                                                    <th>Callsign</th>
                                                    <th>Origen</th>
                                                    <th>Destino</th>
                                                    <th>Aeronave</th>
                                                    <th>Estado</th>
                                                    <th>Información</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($flights['departure']) > 0)
                                                    @foreach ($flights['departure'] as $item)
                                                        @php
                                                            // dd($item);
                                                            $timeDeparture = \Carbon\Carbon::parse($item->flightPlan->departureTime);
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $timeDeparture->format('H:i\z') }}</td>
                                                            <td>
                                                                @php
                                                                    $icao1L = mb_substr($item->callsign, 0, 1);
                                                                    $icao3L = mb_substr($item->callsign, 0, 3);
                                                                    $icao2L = mb_substr($item->callsign, 0, 2);
                                                                    if ($icao1L == 'N' && $icao3L !== 'NSE' && $icao3L !== 'NKS') {
                                                                        $airline = $icao1L;
                                                                    } elseif ($icao2L == 'HK') {
                                                                        $airline = $icao2L;
                                                                    } else {
                                                                        $airline = $icao3L;
                                                                    }
                                                                @endphp
                                                                @if (File::exists(public_path("logos-icao/$airline.png")))
                                                                    <img class="airlineslogo"
                                                                        src="{{ asset("logos-icao/$airline.png") }}" />
                                                                @endif


                                                            </td>
                                                            <td><a href="https://www.ivao.aero/member?id={{ $item->userId }}"
                                                                    target="_blank"
                                                                    title="Ver perfil del piloto">{{ $item->callsign }}</a>
                                                            </td>
                                                            <td>{{ $item->flightPlan->departureId }}</td>
                                                            <td>{{ $item->flightPlan->arrivalId }}</td>
                                                            <td>{{ $item->flightPlan->aircraft->icaoCode }}</td>
                                                            <td>{{ __($item->lastTrack->state) }}</td>
                                                            <td><a href="{{ route('front.fasttrack', $item->callsign) }}"
                                                                    class=""> Ver</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="8">No hay vuelos</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!--end of tabs container-->

                </div>
            </div>
        </div>
    </section>
    <!-- end: VUELOS -->

    {{-- <!-- SERVICES -->
    <section>
        <div class="container">
            <div class="heading-text heading-section text-center">
                <h2>SERVICES</h2>
                <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor cursumus.
                </p>
            </div>
            <div class="row">
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="0">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-plug"></i></a>
                        </div>
                        <h3>Powerful template</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="200">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-desktop"></i></a>
                        </div>
                        <h3>Flexible Layouts</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="400">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-cloud"></i></a>
                        </div>
                        <h3>Retina Ready</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="600">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="far fa-lightbulb"></i></a>
                        </div>
                        <h3>Fast processing</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="800">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-trophy"></i></a>
                        </div>
                        <h3>Unlimited Colors</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="1000">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-thumbs-up"></i></a>
                        </div>
                        <h3>Premium Sliders</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="1200">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-rocket"></i></a>
                        </div>
                        <h3>Modern Design</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="1400">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-flask"></i></a>
                        </div>
                        <h3>Clean Modern Code</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4" data-animate="fadeInUp" data-animate-delay="1600">
                    <div class="icon-box effect medium border small">
                        <div class="icon">
                            <a href="#"><i class="fa fa-umbrella"></i></a>
                        </div>
                        <h3>Free Updates & Support</h3>
                        <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor
                            cursumus.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end: SERVICES --> --}}
    <!-- COUNTERS -->
    <section class="text-light p-t-150 p-b-150 " data-bg-parallax="{{ asset('img/vfr.jpeg') }}">
        <div id="particles-dots" class="particles"></div>
        <div class="bg-overlay"></div>
        <div class="shape-divider" data-style="4"></div>
        <div class="container">
            <div class="heading-text heading-section text-center m-b-40" data-animate="fadeInUp">
                <h2>Estadisticas</h2>
                <span class="lead">Las principales estadísticas de nuestra división</span>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="text-center">
                        <div class="icon"><i class="fa fa-3x fa-users"></i></div>
                        <div class="counter"> <span data-speed="1500" data-refresh-interval="400" data-to="2087"
                                data-from="1000" data-seperator="true"></span> </div>
                        <div class="seperator seperator-small"></div>
                        <p>Miembros Activos</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="text-center">
                        <div class="icon"><i class="fa fa-3x fa-user"></i></div>
                        <div class="counter"> <span data-speed="1500" data-refresh-interval="400" data-to="1331"
                                data-from="100" data-seperator="true"></span> </div>
                        <div class="seperator seperator-small"></div>
                        <p>Pilotos</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="text-center">
                        <div class="icon"><i class="fa fa-3x fa-broadcast-tower"></i></div>
                        <div class="counter"> <span data-speed="1500" data-refresh-interval="400" data-to="612"
                                data-from="100" data-seperator="true"></span> </div>
                        <div class="seperator seperator-small"></div>
                        <p>Controladores</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="text-center">
                        <div class="icon"><i class="fa fa-3x fa-chart-line"></i></div>
                        <div class="counter"> <span data-speed="1500" data-refresh-interval="400" data-to="7"
                                data-from="100" data-seperator="true"></span> </div>
                        <div class="seperator seperator-small"></div>
                        <p>Puesto de la división</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end: COUNTERS -->

    {{-- <!-- CLIENTS -->
    <section class="p-t-60">
        <div class="container">
            <div class="heading-text heading-section text-center">
                <h2>CLIENTS</h2>
                <span class="lead">Our awesome clients we've had the pleasure to work with! </span>
            </div>
            <div class="carousel client-logos" data-items="6" data-items-sm="4" data-items-xs="3" data-items-xxs="2"
                data-margin="20" data-arrows="false" data-autoplay="true" data-autoplay="3000" data-loop="true">
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/1.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/2.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/3.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/4.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/5.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/6.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/7.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/8.png') }}"> </a>
                </div>
                <div>
                    <a href="#"><img alt="" src="{{ asset('theme-1/images/clients/9.png') }}"> </a>
                </div>
            </div>
        </div>
    </section>
    <!-- end: CLIENTS --> --}}
    {{-- <!-- TEAM -->
    <section class="background-grey">
        <div class="container">
            <div class="heading-text heading-section text-center">
                <h2>MEET OUR TEAM</h2>
                <p>Lorem ipsum dolor sit amet, consecte adipiscing elit. Suspendisse condimentum porttitor cursumus.
                </p>
            </div>
            <div class="row team-members">
                <div class="col-lg-3">
                    <div class="team-member">
                        <div class="team-image">
                            <img src="{{ asset('theme-1/images/team/6.jpg') }}">
                        </div>
                        <div class="team-desc">
                            <h3>Alea Smith</h3>
                            <span>Software Developer</span>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing tristique hendrerit laoreet. </p>
                            <div class="align-center">
                                <a class="btn btn-xs btn-slide btn-light" href="#">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="100">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="118">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="mailto:#" data-width="80">
                                    <i class="icon-mail"></i>
                                    <span>Mail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="team-member">
                        <div class="team-image">
                            <img src="{{ asset('theme-1/images/team/7.jpg') }}">
                        </div>
                        <div class="team-desc">
                            <h3>Ariol Doe</h3>
                            <span>Software Developer</span>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing tristique hendrerit laoreet. </p>
                            <div class="align-center">
                                <a class="btn btn-xs btn-slide btn-light" href="#">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="100">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="118">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="mailto:#" data-width="80">
                                    <i class="icon-mail"></i>
                                    <span>Mail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="team-member">
                        <div class="team-image">
                            <img src="{{ asset('theme-1/images/team/8.jpg') }}">
                        </div>
                        <div class="team-desc">
                            <h3>Emma Ross</h3>
                            <span>Software Developer</span>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing tristique hendrerit laoreet. </p>
                            <div class="align-center">
                                <a class="btn btn-xs btn-slide btn-light" href="#">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="100">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="118">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="mailto:#" data-width="80">
                                    <i class="icon-mail"></i>
                                    <span>Mail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="team-member">
                        <div class="team-image">
                            <img src="{{ asset('theme-1/images/team/9.jpg') }}">
                        </div>
                        <div class="team-desc">
                            <h3>Victor Loda</h3>
                            <span>Software Developer</span>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing tristique hendrerit laoreet. </p>
                            <div class="align-center">
                                <a class="btn btn-xs btn-slide btn-light" href="#">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="100">
                                    <i class="fab fa-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="#" data-width="118">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                <a class="btn btn-xs btn-slide btn-light" href="mailto:#" data-width="80">
                                    <i class="icon-mail"></i>
                                    <span>Mail</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end: TEAM --> --}}
@endsection
@push('scripts')
    <!-- Partical js base file  -->
    <script src="{{ asset('theme-1/plugins/particles/particles.js') }}" type="text/javascript"></script>
    <!--Particles-->
    <script src="{{ asset('theme-1/plugins/particles/particles-dots.js') }}" type="text/javascript"></script>
    {{-- <script>
        $(function() {
            $('a[href*=#]').on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $($(this).attr('href')).offset().top
                }, 500, 'linear');
            });
        });
    </script> --}}
@endpush
