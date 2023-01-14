<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Web Team" />
    <meta name="description"
        content="En IVAO Colombia, división de IVAO (International Virtual Aviation Organisation), puedes volar o controlar un aeropuerto con el máximo profesionalismo posible. Además es totalmente gratis.">
    <link rel="icon" type="image/png" href="{{ asset('theme-1/images/favicon.png') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Document title -->
    <title>IVAO CO</title>
    <!-- Stylesheets & Fonts -->
    <link href="{{ asset('theme-1/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('theme-1/css/style.css') }}" rel="stylesheet">

</head>

<body>
    <style>
        /* .social-icons li a {
            width: 45px;
            height: 45px;
            font-size: 2.8rem;
        } */
    </style>
    <!-- Body Inner -->
    <div class="body-inner">
        <section class="text-center p-t-50 p-b-50 text-light h-100"
            style="background-image:url('{{ asset('theme-1/images/twotter.jpeg') }}');  background-size: cover; height: 100vh !important;">
            <div class="bg-overlay" style="opacity: 1"></div>
            <div class="container">

                <div data-animate="fadeInUp">
                    <img src="{{ asset('theme-1/images/logoivao.png') }}" alt="Logo" style="max-height: 200px" />
                </div>

                <div data-animate="fadeInUp">
                    <h1>{{ __('comingsoon.welaunch') }}</h1>
                </div>

                <div class="countdown countdown-light m-b-40" data-countdown="2022/10/10 00:00:00z"
                    data-animate="fadeInUp"></div>

                <div data-animate-delay="600" data-animate="fadeInUp">

                    <p class="lead">{{ __('comingsoon.construction') }}</p>
                </div>
                <hr class="space">
                <div class="row">
                    <div class="col-12 center">
                        <div data-animate-delay="600" data-animate="fadeInUp">
                            <div class="widget clearfix widget-newsletter ">
                                <div class="social-icons social-icons-colored-hover text-center">
                                    <ul>
                                        <li class="social-discord">
                                            <a href="https://discord.gg/HKA2DUdh7j" target="_blank"><i
                                                    class="fab fa-discord"></i></a>
                                        </li>
                                        <li class="social-facebook">
                                            <a href="https://www.facebook.com/ivaoco" target="_blank"><i
                                                    class="fab fa-facebook-f"></i></a>
                                        </li>
                                        <li class="social-twitter">
                                            <a href="#"><i class="fab fa-twitter" target="_blank"></i></a>
                                        </li>
                                        <li class="social-instagram">
                                            <a href="https://www.instagram.com/ivao.co/" target="_blank"><i
                                                    class="fab fa-instagram"></i></a>
                                        </li>
                                        <li class="social-youtube">
                                            <a href="https://www.youtube.com/c/IVAOColombia" target="_blank"><i
                                                    class="fab fa-youtube"></i></a>
                                        </li>

                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                {{-- <hr class="space"> --}}
                <div class="text-light" data-animate-delay="800" data-animate="fadeInUp">&copy;IVAO CO 2022.</div>
            </div>

        </section>


    </div>
    <!-- end: Body Inner -->

    <!-- Scroll top -->
    <a id="scrollTop"><i class="icon-chevron-up"></i><i class="icon-chevron-up"></i></a>
    <!--Plugins-->
    <script src="{{ asset('theme-1/js/jquery.js') }}"></script>
    <script src="{{ asset('theme-1/js/plugins.js') }}"></script>

    <!--Template functions-->
    <script src="{{ asset('theme-1/js/functions.js') }}"></script>

    <script>
        var $countdownTimer = $(".countdown");
        if ($countdownTimer.length > 0) {
            //Check if countdown plugin is loaded
            if (typeof $.fn.countdown === "undefined") {
                INSPIRO.elements.notification(
                    "Warning",
                    "jQuery countdown plugin is missing in plugins.js file.",
                    "danger"
                );

            }
            $(".countdown").each(function(index, element) {
                var elem = $(element),
                    finalDate = elem.attr("data-countdown");

                elem.countdown(finalDate, function(event) {
                        elem.html(
                            event.strftime(
                                '<div class="countdown-container"><div class="countdown-box"><div class="number">%-D</div><span>{{ __('comingsoon.days') }}</span></div>' +
                                '<div class="countdown-box"><div class="number">%H</div><span>{{ __('comingsoon.hours') }}</span></div>' +
                                '<div class="countdown-box"><div class="number">%M</div><span>{{ __('comingsoon.minutes') }}</span></div>' +
                                '<div class="countdown-box"><div class="number">%S</div><span>{{ __('comingsoon.seconds') }}</span></div></div>'
                            )
                        );
                    })
                    .on("finish.countdown", function() {
                        location.reload()
                    });;
            });
        }
    </script>

</body>

</html>
