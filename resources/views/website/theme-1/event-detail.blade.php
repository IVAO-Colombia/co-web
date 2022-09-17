@extends('website.theme-1.layout.theme-1')
@section('content')
    {{-- <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/skmd.jpeg') }}');">
            <div class="bg-overlay" data-style="2"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ $event->title }}</h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider --> --}}

    <section id="page-title" class="background-dark " style="padding: 120px 0 80px 0;">
        <div class="container">
            <div class="page-title text-light heading-text">
                <h1 class="text-uppercase py-3" style="font-size: 3rem;">{{ $event->title }}</h1>
                <img src="{{ asset('storage/events/' . $event->image) }}" alt="{{ $event->title }}" class="pt-2 w-100">

                {{-- <span>Inspiration comes of working every day.</span> --}}
            </div>

        </div>
    </section>

    <section style="padding: 80px 0 80px 0;">
        <div class="container">
            <div class="row">

                <div class="col-lg-12">
                    <div class="text-center">

                    </div>

                    <div class="py-5">
                        {!! $event->description !!}
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
