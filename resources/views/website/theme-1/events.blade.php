@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/aircraft-air.jpg') }}');">
            <div class="bg-overlay" data-style="2"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ __('Events') }}</h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->

    <section class="content" id="events">
        <div class="container">

            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                @php
                    $counter = 0;
                @endphp
                @foreach ($events as $item)
                    <!-- Post item-->
                    <div class="post-item border wow" data-animate="fadeInLeft" data-animate-delay="{{ $counter * 400 }}">
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
                                <h2
                                    style=" white-space: nowrap;
                                overflow: hidden;
                                text-overflow: ellipsis;">
                                    <a href="{{ route('front.event_detail', $item->slug) }}"
                                        class="text-capitalize">{{ $item->title }}
                                    </a>
                                </h2>
                                <p
                                    style=" white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($item->description), 200) !!}</p>
                                <a href="{{ route('front.event_detail', $item->slug) }}" class='item-link'>Leer Más <i
                                        class="icon-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- end: Post item-->
                    @php
                        $counter++;
                    @endphp
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                {{ $events->links() }}
            </div>
        </div>
    </section>
@endsection
