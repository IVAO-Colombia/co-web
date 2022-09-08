@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative h-40" data-height-xs="360">
        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/skmd.jpeg') }}');">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->
                    <span class="strong">Detalle del vuelo</span>
                    <h1> <b>{{ $flight->callsign }} </b></h1>
                    <!-- end: Captions -->
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->
    @php
        $icao1L = mb_substr($flight->callsign, 0, 1);
        $icao3L = mb_substr($flight->callsign, 0, 3);
        $icao2L = mb_substr($flight->callsign, 0, 2);
        if ($icao1L == 'N' && $icao3L !== 'NSE' && $icao3L !== 'NKS') {
            $airline = $icao1L;
        } elseif ($icao2L == 'HK') {
            $airline = $icao2L;
        } else {
            $airline = $icao3L;
        }
    @endphp

    <section>
        <div class="container">
            <div class="row ">

                <div class="col-12 mb-2">
                    <h1>Plan de Vuelo</h1>
                </div>

                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <b>Estado:</b>
                                </td>
                                <td>
                                    {{ __($flight->lastTrack->state) }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Operador:</b></td>
                                <td>
                                    @if (File::exists(public_path("logos-icao/$airline.png")))
                                        <img src="{{ asset("logos-icao/$airline.png") }}" style="max-height: 130px" />
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><b>Aeronave:</b></td>
                                <td>
                                    {{ $flight->flightPlan->aircraftId }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Regla de Vuelo:</b></td>
                                <td>
                                    {{ $flight->flightPlan->flightRules }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Nivel:</b></td>
                                <td>
                                    {{ $flight->flightPlan->level }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Velocidad:</b></td>
                                <td>
                                    {{ $flight->flightPlan->speed }}
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <b> Origen:</b>
                                </td>
                                <td>
                                    {{ $departureAirport->municipality ?? '' }}/{{ $departureAirport->iata ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Destino:</b></td>
                                <td>
                                    {{ $arrivalAirport->municipality ?? '' }}/{{ $arrivalAirport->iata ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td><b>Alterno:</b></td>
                                <td>
                                    {{ $flight->flightPlan->alternativeId }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Ruta:</b>
                                </td>
                                <td>
                                    {{ $flight->flightPlan->route }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Personas abordo:</b>
                                </td>
                                <td>
                                    {{ $flight->flightPlan->peopleOnBoard }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Observaciones:</b>
                                </td>
                                <td>
                                    {{ $flight->flightPlan->remarks }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <h1>Seguimiento del vuelo</h1>
                    <br>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <td>
                                    <b>Velocidad de Tierra:</b>
                                </td>
                                <td>{{ $flight->lastTrack->groundSpeed }} kts</td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Altitud:</b>
                                </td>
                                <td>{{ $flight->lastTrack->altitude }} ft</td>
                            </tr>
                            <tr>
                                <td><b>Rumbo:</b></td>
                                <td>{{ $flight->lastTrack->heading }} </td>
                            </tr>
                            <tr>
                                <td><b>Transponder:</b></td>
                                <td>{{ $flight->lastTrack->transponder }}</td>
                            </tr>
                            <tr>
                                <td><b>Coordenadas:</b></td>
                                <td>
                                    [{{ $flight->lastTrack->longitude }},
                                    {{ $flight->lastTrack->latitude }}
                                    ]
                                </td>
                            </tr>
                            <tr>
                                <td><b>Simulador</b></td>
                                <td>{{ $flight->pilotSession->simulatorId }}</td>
                            </tr>
                            <tr>
                                <td><b>Webeye</b></td>
                                <td><a href="https://webeye.ivao.aero/?pilotId={{ $flight->id }}" target="_blank"
                                        rel="noopener noreferrer">Ver</a></td>
                            </tr>
                        </table>
                    </div>

                </div>



                <div class="col-md-12 my-4 wow fadeLeft">
                    <h1>Mapa</h1>
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
            height: 80vh;
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript">
        var points = [
            @if ($departureAirport)
                [{{ $departureAirport->longitude }}, {{ $departureAirport->latitude }}],
            @endif
            [{{ $flight->lastTrack->longitude }},
                {{ $flight->lastTrack->latitude }}
            ],
            @if ($arrivalAirport)
                [{{ $arrivalAirport->longitude }}, {{ $arrivalAirport->latitude }}]
            @endif
        ];

        var lineStyle = new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: '#FF0000',
                width: 2
            }),
        });

        var aeroStyle = new ol.style.Style({
            stroke: new ol.style.Stroke({
                width: 3,
                color: [0, 0, 100, 0.50]
            }),
            fill: new ol.style.Fill({
                color: [0, 0, 100, 0.50]
            }),
            image: new ol.style.Circle({
                stroke: new ol.style.Stroke({
                    color: 'red',
                    width: 3
                }),
                fill: new ol.style.Fill({
                    color: [0, 0, 100, 0.50]
                }),
                radius: 10,
            })

        });


        var iconFeature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.transform([{{ $flight->lastTrack->longitude }},
                    {{ $flight->lastTrack->latitude }}
                ], 'EPSG:4326',
                'EPSG:3857')),

        });

        @if ($departureAirport)
            var iconDeparture = new ol.Feature({
                geometry: new ol.geom.Circle(ol.proj.transform([{{ $departureAirport->longitude }},
                        {{ $departureAirport->latitude }}
                    ], 'EPSG:4326',
                    'EPSG:3857'), 7000),

            });
            iconDeparture.setStyle(aeroStyle);
        @endif


        @if ($arrivalAirport)
            var iconArrival = new ol.Feature({
                geometry: new ol.geom.Circle(ol.proj.transform([{{ $arrivalAirport->longitude }},
                        {{ $arrivalAirport->latitude }}
                    ], 'EPSG:4326',
                    'EPSG:3857'), 7000),

            });
            iconArrival.setStyle(aeroStyle);
        @endif




        var featureLine = new ol.Feature({
            geometry: new ol.geom.LineString(points).transform('EPSG:4326', 'EPSG:3857'),
            name: "Line",

        });


        featureLine.setStyle(lineStyle);


        // featureLine.setStyle(
        //     new ol.style.Style({
        //         stroke: new ol.style.Style({
        //             color: [255, 0, 0, 255],
        //             width: 3
        //         })
        //     })
        // );


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
            features: [featureLine, iconDeparture, iconArrival, iconFeature]
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
                zoom: 8
            })
        });
        map.getView().fit(vectorSource.getExtent());
    </script>
@endpush
