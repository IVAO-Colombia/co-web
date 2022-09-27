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

                    <h1>{{ __('Trainings') }}</h1>
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
                <div class="col-md-12 my-4 wow fadeLeft">
                    <p class="">Solo podrás pedir un entrenamiento a la vez.</p>
                    @if ($textratingATC != null)
                        @if (count($trainingAtcOpen) < 1)
                            <div class="modal-instance">
                                <a class="btn modal-trigger" href="#" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    <span class="btn__text">
                                        Solicitar Entrenamiento ATC
                                    </span>
                                </a>



                            </div>
                            <!--end of modal instance-->
                        @endif
                    @endif
                    @if ($textratingpilot != null)
                        @if (count($trainingPilotOpen) < 1)
                            <div class="modal-instance">
                                <a class="btn modal-trigger" href="#" data-bs-toggle="modal" data-bs-target="#piloto">
                                    <span class="btn__text">
                                        Solicitar Entrenamiento Piloto
                                    </span>
                                </a>


                            </div>

                            <!--end of modal instance-->
                        @endif
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 my-2 table-responsive">
                    <table class="table table-border">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tipo entrenamiento</th>
                                <th>Rango</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Entrenamiento</th>
                                <th>Entrenador</th>
                                <th>Obs.</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trainings as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->typetraining }}</td>
                                    <td>
                                        @if ($item->typetraining == 'PILOTO')
                                            {{ $ratingPilot[$item->rating] }}
                                        @else
                                            {{ $ratingATC[$item->rating] }}
                                        @endif
                                    </td>

                                    <td>{{ $item->created_at->locale('es')->isoFormat('LLL') }} HLC</td>
                                    <td>{{ $item->date_training ? $item->date_training->locale('es')->isoFormat('LLL') . ' HLC' : 'Esperando asignación' }}
                                    </td>
                                    <td>
                                        @if ($item->trainer)
                                            {{ optional($item->trainer)->name }}
                                        @else
                                            Esperando asignación
                                        @endif
                                    </td>
                                    <td>
                                        {{ $item->note }}
                                    </td>
                                    <td>{{ $item->status == 1 ? 'Abierto' : 'Cerrado' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>


    </section>


    <section class="imageblock switchable feature-large space--sm bg--secondary">
        <div class="imageblock__content col-lg-6 col-md-4 pos-right">
            <div class="background-image-holder" style="background: url('{{ asset('img/simulador.jpg') }}'); opacity: 1;">
                <img alt="image" src="{{ asset('img/simulador.jpg') }}">
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-7">
                    {{-- <h2>Temas para entrenamientos</h2> --}}
                    <p class="lead">
                        “Lo maravilloso de aprender algo, es que nadie puede arrebatárnoslo”. —B.B. King
                    </p>
                </div>
            </div>
            <!--end of row-->
        </div>
        <!--end of container-->
    </section>

    <div class="modal fade" id="piloto" tabindex="-1" aria-labelledby="pilotoLabel" aria-hidden="true"
        data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Solicitud de Entrenamiento para piloto</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="boxed boxed--lg">

                        <hr class="short">
                        <form action="{{ route('front.trainingpilot') }}" method="POST">
                            @csrf
                            <div>
                                <div class="lead">
                                    {{ auth()->user()->firstname }} está solicitando
                                    entrenamiento
                                    para
                                    el rango
                                    <br>
                                    <div class="my-3">
                                        <img src="https://ivao.aero/data/images/ratings/pilot/{{ auth()->user()->ratingpilot + 1 }}.gif"
                                            class="img-responsive mb-0" style="vertical-align: middle;" />
                                        <span class="">
                                            {{ $textratingpilot }}
                                        </span>
                                    </div>

                                    <button type="submit" class="btn btn--sm btn--primary type--uppercase btn--icon">
                                        <span>Solicitar</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-content">
            <div class="boxed boxed--lg">
                <h2>Solicitud de Entrenamiento para ATC</h2>
                <hr class="short">
                <form action="{{ route('front.trainingatc') }}" method="POST">
                    @csrf
                    <div>
                        <div class="lead">
                            {{ auth()->user()->firstname }} está solicitando entrenamiento para
                            el rango
                            <br>
                            <div class="my-3">
                                <img src="https://ivao.aero/data/images/ratings/atc/{{ auth()->user()->ratingatc + 1 }}.gif"
                                    class="img-responsive mb-0" style="vertical-align: middle;" />
                                <span class="">
                                    {{ $textratingATC }}
                                </span>
                            </div>

                            <button type="submit" class="btn btn--sm btn--primary type--uppercase btn--icon">
                                <span>Solicitar</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-close modal-close-cross"></div>
        </div>
    </div>
@endsection
