@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/0103.jpg') }}');">
            <div class="bg-overlay" data-style="10"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ __('Events Calendar') }}</h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->

    <section id="page-content" class="no-sidebar">
        <div class="container">
            <!-- Calendar -->

            <div class="row">
                <div class="col-lg-12">
                    <div id="calendar"></div>
                </div>
            </div>
            <!-- end: Calendar -->
        </div>
    </section>
@endsection
@push('styles')
    <!-- Full Calendar files -->
    <link href='{{ asset('theme-1/plugins/fullcalendar/fullcalendar.min.css') }}' rel='stylesheet' />
@endpush
@push('scripts')
    <script src="{{ asset('theme-1/js/functions.js') }}"></script>

    <script src='{{ asset('theme-1/plugins/moment/moment.min.js') }}'></script>
    <script src='{{ asset('theme-1/plugins/fullcalendar/fullcalendar.min.js') }}'></script>
    <script>
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
            },
            defaultDate: Date.now(),
            navLinks: true, // can click day/week names to navigate views
            editable: false,
            eventLimit: true, // allow "more" link when too many events
            events: [
                @foreach ($events as $item)
                    {
                        title: '{{ $item->title }}',
                        start: '{{ $item->start_publish_date }}',
                        className: 'fc-event-primary',
                        url: '{{ route('front.event_detail', $item->slug) }}',
                    },
                @endforeach
            ]
        });
    </script>
@endpush
