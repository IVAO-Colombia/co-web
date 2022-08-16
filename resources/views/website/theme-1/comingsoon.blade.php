<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="INSPIRO" />
    <meta name="description" content="Themeforest Template Polo, html template">
    <link rel="icon" type="image/png" href="images/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Document title -->
    <title>POLO | The Multi-Purpose HTML5 Template</title>
    <!-- Stylesheets & Fonts -->
    <link href="{{ asset('theme-1/css/plugins.css') }}" rel="stylesheet">
    <link href="{{ asset('theme-1/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <!-- Body Inner -->
    <div class="body-inner">
        <section class="text-center p-t-200 p-b-200 text-light"
            style="background-image:url({{ asset('theme-1/homepages/coming-soon/images/1.jpg') }});">
            <div class="container">
                <div data-animate="fadeInUp">
                    <h1>{{ __('comingsoon.welaunch') }}</h1>
                </div>

                <div class="countdown countdown-light m-b-40" data-countdown="2022/10/01 19:00:00"
                    data-animate="fadeInUp"></div>

                <div data-animate-delay="600" data-animate="fadeInUp">

                    <p class="lead">Our website is under construction, we are working very hard to give you the best
                        experience on our new web site.</p>
                </div>
                <hr class="space">
                {{-- <div class="row">
                    <div class="col-6 center">
                        <div data-animate-delay="600" data-animate="fadeInUp">
                            <div class="widget clearfix widget-newsletter">
                                <form class="widget-subscribe-form" novalidate action="include/subscribe-form.php"
                                    role="form" method="post">
                                    <small>Stay ready, we`re launching soon</small>
                                    <div class="input-group form-control-lg">
                                        <input type="email" required name="widget-subscribe-form-email"
                                            class="form-control required email" placeholder="Enter your Email">
                                        <button type="submit" id="widget-subscribe-submit-button"
                                            class="btn btn-danger">Subscribe</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div> --}}


                <hr class="space">
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

</body>

</html>
