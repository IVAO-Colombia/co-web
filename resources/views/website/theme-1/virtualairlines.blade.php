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

                    <h1>{{ __('Virtual Airlines') }}</h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->


    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <h2> Nuestras Aerolineas Virtuales</h2>

                </div>
            </div>

            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                @php
                    $counter = 0;
                @endphp
                @foreach ($virtualairlines as $item)
                    <!-- Post item-->
                    <div class="post-item border wow" data-animate="fadeInLeft" data-animate-delay="{{ $counter * 400 }}">
                        <div class="post-item-wrap">
                            <div class="post-image">
                                <a href="{{ url($item->website) }}" target="_blank">
                                    <img alt="{{ $item->name }}"
                                        src="{{ asset('storage/virtualairlines/' . $item->imagen) }}">
                                </a>
                                @if (false)
                                    <span class="post-meta-category">
                                        <a href="">Lifestyle</a>
                                    </span>
                                @endif
                            </div>
                            <div class="post-item-description">
                                <span class="post-meta-date"></span>

                                <h2>
                                    <a href="{{ url($item->website) }}" class="text-capitalize"
                                        target="_blank">{{ $item->name }}
                                    </a>
                                </h2>
                                <p
                                    style=" white-space: nowrap;
                                    overflow: hidden;
                                    text-overflow: ellipsis;">
                                    {!! \Illuminate\Support\Str::limit(strip_tags($item->description), 200) !!}</p>
                                <a href="{{ url($item->website) }}" class='item-link' target="_blank">{{ __('Website') }} <i
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

        </div>
    </section>
@endsection
