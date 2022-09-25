@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/skmd.jpeg') }}');">
            <div class="bg-overlay" data-style="2"></div>
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
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: [
                @foreach ($events as $item)
                    {
                        title: '{{ $item->title }}',
                        start: '{{ $item->start_publish_date }} ',
                        className: 'fc-event-primary'
                    },
                @endforeach
                //, {
                //     title: 'Long Event',
                //     start: '2021-01-07',
                //     end: '2021-01-10',
                //     className: 'fc-event-primary'
                // }, {
                //     id: 999,
                //     title: 'Repeating Event',
                //     start: '2021-01-09T16:00:00'
                // }, {
                //     id: 999,
                //     title: 'Repeating Event',
                //     start: '2021-01-16T16:00:00'
                // }, {
                //     title: 'Conference',
                //     start: '2021-01-11',
                //     end: '2021-01-13',
                //     className: 'fc-event-warning',
                //     description: "Lorem ipsum dolor sit incid idunt ut",
                // }, {
                //     title: 'Meeting',
                //     start: '2021-01-12T10:30:00',
                //     end: '2021-01-12T12:30:00'
                // }, {
                //     title: 'Lunch',
                //     start: '2021-01-12T12:00:00'
                // }, {
                //     title: 'Meeting',
                //     start: '2021-01-12T14:30:00'
                // }, {
                //     title: 'Happy Hour',
                //     start: '2021-01-12T17:30:00'
                // }, {
                //     title: 'Dinner',
                //     start: '2021-01-12T20:00:00'
                // }, {
                //     title: 'Birthday Party',
                //     start: '2021-01-13T07:00:00',
                //     className: 'fc-event-danger'
                // }, {
                //     title: 'Click for Google',
                //     url: 'http://google.com/',
                //     start: '2021-01-28',
                //     className: 'fc-event-info'
                // }
            ]
        });
    </script>
@endpush
