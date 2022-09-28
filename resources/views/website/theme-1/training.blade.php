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

    @if (auth()->user()->division == env('APP_DIVISION'))
        <section>
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-12 my-4 wow fadeLeft">
                        <p class="">Solo podrás pedir un entrenamiento a la vez.</p>
                        @if ($textratingATC != null)
                            @if (count($trainingAtcOpen) < 1)
                                <a class="btn modal-trigger" href="#" data-bs-toggle="modal" data-bs-target="#atc">
                                    <span class="btn__text">
                                        Solicitar Entrenamiento ATC
                                    </span>
                                </a>
                            @endif
                        @endif
                        @if ($textratingpilot != null)
                            @if (count($trainingPilotOpen) < 1)
                                <a class="btn modal-trigger" href="#" data-bs-toggle="modal" data-bs-target="#piloto">
                                    <span class="btn__text">
                                        Solicitar Entrenamiento Piloto
                                    </span>
                                </a>
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
                                            {{ getRatingTraining($item) }}

                                        </td>

                                        <td>{{ $item->created_at->locale('es')->isoFormat('LLL') }}z</td>
                                        <td>{{ $item->date_training ? $item->date_training->locale('es')->isoFormat('LLL') . 'z' : 'Esperando asignación' }}
                                        </td>
                                        <td>
                                            @if ($item->trainer)
                                                {{ optional($item->trainer)->firstname }}
                                                {{ optional($item->trainer)->lastname }}
                                            @else
                                                Esperando asignación
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->note }}
                                        </td>
                                        <td>
                                            <span>

                                            </span>
                                            {{ __(getStatusTraining($item->status)) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    {{ $trainings->links() }}
                </div>
            </div>
        </section>
    @else
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-danger" role="alert">
                            {{ __('you do not belong to the division, request training in your division.') }}
                            <a href="https://{{ auth()->user()->division }}.ivao.aero"
                                class="text-white text-bold">Here</a>
                        </div>


                    </div>
                </div>
            </div>
        </section>
    @endif


    <section class="" data-bg-parallax="{{ asset('img/sksm-exterior.jpg') }}">
        <div class="bg-overlay"></div>
        <div class="shape-divider" data-style="13"></div>
        <div class="container">

            <div class="row align-items-center text-white">

                <div class="col-lg-12">
                    <div class="heading-text heading-section mt-5">
                        <h2>“Lo maravilloso de aprender algo, es que nadie puede arrebatárnoslo”. —B.B. King</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <section class="imageblock switchable feature-large space--sm bg--secondary">
        <div class="imageblock__content col-lg-6 col-md-4 pos-right">
            <div class="background-image-holder"
                style="background: url('{{ asset('img/sksm-exterior.jpg') }}'); opacity: 1;">

            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-7">

                </div>
            </div>
            <!--end of row-->
        </div>
        <!--end of container-->
    </section> --}}
    @if ($textratingpilot != null && count($trainingPilotOpen) < 1)
        <div class="modal fade" id="piloto" tabindex="-1" aria-labelledby="pilotoLabel" aria-hidden="true"
            data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Solicitud de Entrenamiento para piloto</h2>
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

                                            <p>{{ auth()->user()->email }}</p>
                                        </div>

                                        <button type="submit" class="btn btn--sm btn--primary type--uppercase btn--icon">
                                            <span>{{ __('Request') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>

                    </div>
                </div>
            </div>
        </div>
    @endif
    @if ($textratingATC != null && count($trainingAtcOpen) < 1)
        <div class="modal fade" id="atc" tabindex="-1" aria-labelledby="atcLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Solicitud de Entrenamiento para ATC</h2>
                    </div>
                    <div class="modal-body">
                        <div class="boxed boxed--lg">

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
                                            <span>{{ __('Request') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
