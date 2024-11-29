@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/atcback.png') }}');">
            <div class="bg-overlay" data-style="10"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <h1>{{ __('ATC') }}</h1>
                </div>
            </div>
        </div>
        <!-- end: Slide 2 -->

    </div>
    <!--end: Inspiro Slider -->

    <section>
        <div class="container">
            <div class="row justify-center flex">
                <div class="content col-md-10 my-2 text-left ">

                    <div class="tag-box tag-box-v1 margin-bottom-40 heading-text heading-light font-light">
                        <h2>Primeros Pasos</h2>
                        <p>El departamento de ATC Ops ha preparado una intuitiva guía y documentos relacionados con el control
                            aéreo en la división. En el mapa interactivo podrán cambiar entre dependencias CTR / APP / TWR. Adicionalmente,
                            los controladores de GND y DEL estarán incluidos en la documentación de TWR.
                        </p>

                        <!-- Map Section -->
                        <div id="map" style="height: 500px;"></div> <!-- This is where your map will be displayed -->

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <script>
        // Create a map centered at a specific latitude and longitude
        var map = L.map('map').setView([4.7749, -74.4194], 5);

        // Add OpenStreetMap tile layer to the map
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Load GeoJSON data
        fetch("{{ asset('theme-1/docs/public/COLACC.geojson') }}")
            .then(response => response.json())
            .then(data => {
                // Add the GeoJSON data to the map
                L.geoJSON(data).addTo(map);
            })
            .catch(error => console.error('Error loading GeoJSON data:', error));
    </script>
@endsection
