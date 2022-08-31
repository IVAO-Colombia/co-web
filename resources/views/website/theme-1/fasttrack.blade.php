@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="360">
        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/skmd.jpeg') }}');">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->
                    <span class="strong">Fast Track</span>
                    <h1>Detalle del vuelo <b>{{ $flight->callsign }} </b></h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->

    <section>
        <div class="container">
            <div class="row justify-content-around">
                <div class="col-md-12 my-2 text-center">
                    <h3>{{ $flight->flightPlan->departureId }} - {{ $flight->flightPlan->arrivalId }}</h3>
                    <p class="lead">Estado: {{ $flight->lastTrack->state }}</p>
                    <p class="lead">Regla de vuelo: {{ $flight->flightPlan->flightRules }} </p>
                    <p class="lead">Altitud: {{ $flight->lastTrack->altitude }}</p>
                    <p class="lead">GS: {{ $flight->lastTrack->groundSpeed }} kts</p>
                    <p class="lead">Transponder: {{ $flight->lastTrack->transponder }}</p>
                    <p class="lead">Ruta: {{ $flight->flightPlan->route }}</p>
                    <p class="lead">Remark: {{ $flight->flightPlan->remarks }}</p>
                    <p class="lead">Personas abordo: {{ $flight->flightPlan->peopleOnBoard }}</p>
                    <p class="lead">Sesion: {{ $flight->pilotSession->simulatorId }}</p>
                </div>
                <div class="col-md-12 my-4 wow fadeLeft">
                    <h3>Mapa</h3>
                    <div id="mapa" class="mapa"></div>

                </div>
            </div>
        </div>
    </section>
@endsection


@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/css/ol.css">
    {{-- <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/build/ol.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/openlayers/4.6.5/ol.js"></script>

    <style>
        .mapa {
            min-height: 400px;
            height: 70vh;
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        var iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([{{ $flight->lastTrack->longitude }},
                    {{ $flight->lastTrack->latitude }}
                ], 'EPSG:4326',
                'EPSG:3857'))
        })

        // var lineFeature = new ol.Feature(
        // new ol.geom.LineString([[-1e7, 1e6], [-1e6, 3e6]]));

        var styleAvion = new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 0.5],
                anchorXUnits: 'fraction',
                anchorYUnits: 'fraction',
                rotation: {{ $flight->lastTrack->heading }} * Math.PI / 180,
                src: '{{ asset('img/icons/medium.png') }}'
            }),
        });
        iconFeature.setStyle(styleAvion);

        var vectorSource = new ol.source.Vector({
            features: [iconFeature]
        });

        var vectorLayer = new ol.layer.Vector({
            source: vectorSource
        });

        var map = new ol.Map({
            target: 'mapa',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                }),
                vectorLayer
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([{{ $flight->lastTrack->longitude }},
                    {{ $flight->lastTrack->latitude }}
                ]),
                zoom: 5
            })
        });
    </script>
@endpush
