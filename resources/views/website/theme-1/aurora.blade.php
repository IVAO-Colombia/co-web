@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/clr-colradar.png') }}');">
            <div class="bg-overlay" data-style="10"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ __('IVAO Aurora') }}</h1>
                    <!-- end: Captions -->
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
                        <p>Aurora es el software usado por IVAO para ejercer el rol de controlador aéreo. Para descargar Aurora
                            dirigete a <a href="https://www.ivao.aero/softdev/software/aurora.asp" class="text-blue-800 font-semibold">IVAO - Descargas</a>.
                            </p>
                        </p>

                        <h2>¿Cómo usar Aurora?</h2>
                        <p>
                            Para usar Aurora IVAO cuenta con una documentación robusta al respecto en su <a href="https://wiki.ivao.aero/es/home/devops/manuals/Aurora_Manual" class="text-blue-800 font-semibold">Wiki</a>.
                            En el caso de la división contamos con un manual para funciones propias del sector colombiano; por ende contamos con los siguientes manuales:
                        </p>

                        <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                            <!-- Static Post Item 1 -->
                            <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="0">
                                <div class="post-item-wrap">
                                    <div class="post-image">
                                        <a href="#link1">
                                            <img alt="Manual Sectorfile" src="{{ asset('img/aurora_manual.png') }}" />
                                        </a>
                                    </div>
                                    <div class="post-item-description">
                                        <span class="post-meta-date"><i class="fa fa-calendar-o"></i> 28 Nov 2024</span>
                                        <h2>
                                            <a href="{{ asset('theme-1/docs/public/Manual Sectorfile SKED-EC.pdf') }}" target="_blank" rel="noopener noreferrer" class="text-capitalize">Manual Sectorfile</a>
                                        </h2>
                                        <p style="white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                            Manual de funciones específicas de la división implementadas en Aurora.
                                        </p>
                                        <a href="{{ asset('theme-1/docs/public/Manual Sectorfile SKED-EC.pdf') }}" target="_blank" rel="noopener noreferrer" class='item-link'>Abrir <i class="icon-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <!-- Static Post Item 2 -->
                            <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="400">
                                <div class="post-item-wrap">
                                    <div class="post-image">
                                        <a href="#link2">
                                            <img alt="Event Title 2" src="{{ asset('img/wip.gif') }}" />
                                        </a>
                                    </div>
                                    <div class="post-item-description">
                                        <span class="post-meta-date"><i class="fa fa-calendar-o"></i> 29 Nov 2024</span>
                                        <h2>
                                            <a href="#link2" class="text-capitalize">CPDLC y DLC</a>
                                        </h2>
                                        <p style="white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                            ¿Cómo usar CPDLC en Aurora? ¿Qué es DLC y cómo se usa?
                                        </p>
                                        <a href="#link2" class='item-link'>Abrir <i class="icon-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>

                            <!-- Static Post Item 3 -->
                            <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="800">
                                <div class="post-item-wrap">
                                    <div class="post-image">
                                        <a href="#link3">
                                            <img alt="Event Title 3" src="{{ asset('img/wip.gif') }}" />
                                        </a>
                                    </div>
                                    <div class="post-item-description">
                                        <span class="post-meta-date"><i class="fa fa-calendar-o"></i> 30 Nov 2024</span>
                                        <h2>
                                            <a href="#link3" class="text-capitalize">Usando Aurora</a>
                                        </h2>
                                        <p style="white-space: wrap; overflow: hidden; text-overflow: ellipsis;">
                                            Aprende cómo usar Aurora en eventos y en control diario.
                                        </p>
                                        <a href="#link3" class='item-link'>Abrir <i class="icon-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2>Colorfiles</h2>
                        <p>
                            Descarga los colorfiles de tu preferencia. Por defecto el Sectorfile de la división usa
                            un esquema de colores lo más similar posible al usado en la vida real por <em>Indra</em>.
                        <br>
                        <h4>Instalación</h4>
                            Para instalar un <code>colorscheme</code> copias el archivo que descargado en la carpeta donde tengas
                            la instalación de Aurora. Usualmente es en <code>C:Aurora\</code>. Luego vas a la carpeta de <code>Colorschemes</code> y pegas el archivo.
                            Es decir, el archivo debe quedar en <code>C:Aurora\Colorschemes\</code>.

                        </p>
                        <h4>Clásicos</h4>
                            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                                <!-- Static Post Item 1 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="0">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/COL RADAR.clr') }}" download="COL RADAR.clr">
                                                <img alt="Manual Sectorfile" src="{{ asset('img/clr-colradar.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/COL RADAR.clr') }}" download="COL RADAR.clr" class="text-capitalize">COL RADAR</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/COL RADAR.clr') }}" download="COL RADAR.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 2 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="400">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/COL IFACTS.clr') }}" download="COL IFACTS.clr">
                                                <img alt="Event Title 2" src="{{ asset('img/clr-colifacts.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/COL IFACTS.clr') }}" download="COL IFACTS.clr" class="text-capitalize">COL IFACTS</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/COL IFACTS.clr') }}" download="COL IFACTS.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 3 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="800">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/RADAR COLOMBIA (Old).clr') }}" download="RADAR COLOMBIA (Old).clr">
                                                <img alt="Event Title 3" src="{{ asset('img/clr-colradarv1.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/RADAR COLOMBIA (Old).clr') }}" download="RADAR COLOMBIA (Old).clr" class="text-capitalize">RADAR COLOMBIA V1</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/RADAR COLOMBIA (Old).clr') }}" download="RADAR COLOMBIA (Old).clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <h4>Extras</h4>
                            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                                <!-- Static Post Item 1 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="0">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/ASDE-X.clr') }}" download="ASDE-X.clr">
                                                <img alt="Manual Sectorfile" src="{{ asset('img/clr-asdex.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/ASDE-X.clr') }}" download="ASDE-X.clr" class="text-capitalize">ASDE-X</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/ASDE-X.clr') }}" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 2 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="400">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/BELGOCONTROL CANAC.clr') }}" download="BELGOCONTROL CANAC.clr">
                                                <img alt="Event Title 2" src="{{ asset('img/clr-belgocontrol.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/BELGOCONTROL CANAC.clr') }}" download="BELGOCONTROL CANAC.clr" class="text-capitalize">BELGOCONTROL</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/BELGOCONTROL CANAC.clr') }}" download="BELGOCONTROL CANAC.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 3 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="800">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/CDS 2000 PALERMO.clr') }}" download="CDS 2000 PALERMO.clr">
                                                <img alt="Event Title 3" src="{{ asset('img/clr-palermo2000.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/CDS 2000 PALERMO.clr') }}" download="CDS 2000 PALERMO.clr" class="text-capitalize">CDS 2000 PALERMO</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/CDS 2000 PALERMO.clr') }}" download="CDS 2000 PALERMO.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                                <!-- Static Post Item 1 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="0">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/COPANS.clr') }}" download="COPANS.clr">
                                                <img alt="Manual Sectorfile" src="{{ asset('img/clr-copans.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/COPANS.clr') }}" download="COPANS.clr" class="text-capitalize">COPANS (Old)</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/COPANS.clr') }}" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 2 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="400">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/COPANS NEW.clr') }}" download="COPANS NEW.clr">
                                                <img alt="Event Title 2" src="{{ asset('img/clr-copansnew.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/COPANS NEW.clr') }}" download="COPANS NEW.clr" class="text-capitalize">COPANS NEW</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/COPANS NEW.clr') }}" download="COPANS NEW.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 3 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="800">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/ERAM.clr') }}" download="ERAM.clr">
                                                <img alt="Event Title 3" src="{{ asset('img/clr-eram.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/ERAM.clr') }}" download="ERAM.clr" class="text-capitalize">ERAM</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/ERAM.clr') }}" download="ERAM.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                                <!-- Static Post Item 1 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="0">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/EUROCAT.clr') }}" download="EUROCAT.clr">
                                                <img alt="Manual Sectorfile" src="{{ asset('img/clr-eurocat.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/EUROCAT.clr') }}" download="EUROCAT.clr" class="text-capitalize">EUROCAT</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/EUROCAT.clr') }}" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 2 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="400">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/NODE S.clr') }}" download="NODE S.clr">
                                                <img alt="Event Title 2" src="{{ asset('img/clr-nodes.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/NODE S.clr') }}" download="NODE S.clr" class="text-capitalize">NODE S</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/NODE S.clr') }}" download="NODE S.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Static Post Item 3 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="800">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/RDS1600.clr') }}" download="RDS1600.clr">
                                                <img alt="Event Title 3" src="{{ asset('img/clr-rds1600.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/RDS1600.clr') }}" download="RDS1600.clr" class="text-capitalize">RDS1600</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/RDS1600.clr') }}" download="RDS1600.clr" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="blog" class="grid-layout post-3-columns m-b-30" data-item="post-item">
                                <!-- Static Post Item 1 -->
                                <div class="post-item border" data-animate="fadeInLeft" data-animate-delay="0">
                                    <div class="post-item-wrap">
                                        <div class="post-image">
                                            <a href="{{ asset('theme-1/docs/public/UK LTCC.clr') }}" download="UK LTCC.clr">
                                                <img alt="Manual Sectorfile" src="{{ asset('img/clr-uk.png') }}" />
                                            </a>
                                        </div>
                                        <div class="post-item-description">
                                            <h2>
                                                <a href="{{ asset('theme-1/docs/public/UK LTCC.clr') }}" download="UK LTCC.clr" class="text-capitalize">UK LTCC</a>
                                            </h2>
                                            <a href="{{ asset('theme-1/docs/public/UK LTCC.clr') }}" class='item-link'>Descargar <i class="icon-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>





                        <h2 class=" ">Fichas de Control</h2>

                        <p>
                            No olvides revisar <a href="{{ route('front.atc') }}"> nuestro mapa interactivo</a> con sugerencias de control para cada posición.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
