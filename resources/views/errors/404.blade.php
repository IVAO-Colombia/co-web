@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- 404 PAGE -->
    <section id="page-title" data-bg-parallax="{{ asset('img/skmd.jpeg') }}">
        <div class="bg-overlay" data-style="11"></div>
        <div class="container">

            <div class="row">
                <div class="col-lg-6">
                    <div class="page-error-404">404</div>
                </div>
                <div class="col-lg-6">
                    <div class="text-start text-white">
                        <h1 class="text-medium">Ooops, This Page Could Not Be Found!</h1>
                        <p class="lead">The page you are looking for might have been removed, had its name changed, or is
                            temporarily unavailable.</p>
                        <div class="seperator m-t-20 m-b-20"></div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end:  404 PAGE -->
@endsection
