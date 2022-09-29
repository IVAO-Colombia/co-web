@extends('website.theme-1.layout.theme-1')
@section('content')
    <!-- Inspiro Slider -->
    <div id="slider" class="inspiro-slider dots-creative" data-height-xs="200">

        <!-- Slide 2 -->
        <div class="slide kenburns" style="background-image:url('{{ asset('img/0091.jpg') }}');">
            <div class="bg-overlay" data-style="10"></div>
            <div class="container">
                <div class="slide-captions text-center text-light">
                    <!-- Captions -->

                    <h1>{{ __('GCA') }}</h1>
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
                <div class="content col-md-12 my-2 ">

                    <div class="tag-box tag-box-v1 margin-bottom-40 heading-text heading-light">
                        <p>El departamento de entrenamiento informa a los
                            interesados acerca de
                            la modificación en las regulaciones de la división respecto a la Aprobación para Controladores
                            Extranjeros o GCA por sus siglas en inglés.
                            IVAO Colombia se encuentra acogido al programa desde el año 2018 bajo las regulaciones T.2,
                            T.2.1, T2.2.2 y T.2.3.
                        </p>



                        <h2>Definiciones</h2>



                        <p>
                            <b>GCA:</b> Acrónimo de Guest Controller Approval, es una aprobación que le permite a miembros
                            de otras
                            divisiones controlar en el espacio aéreo colombiano.
                            <br>

                            <b>FRA:</b> Facility Rating Assignments, establece los requerimientos mínimos para controlar una
                            dependencia en específico.
                        </p>

                        <h2 class=" ">Requisitos</h2>

                        <p>
                            La división colombiana espera que los aspirantes al GCA cuenten con una calificación que sea
                            equivalente a la posición que desea controlar. Por esto solo se tendrán en cuenta los usuarios
                            que ostenten rango ADC (Aerodrome Controller) o superior. La siguiente tabla muestra las
                            posiciones que se pueden cubrir según el rango:
                        </p>

                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                <strong>Rango</strong>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <strong>Posici&oacute;n de Control</strong>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Aerodrome Controller (ADC)</p>
                                        </td>
                                        <td>
                                            <p>
                                                DEL/GND/TWR/<strong>*APP</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Approach Controller (APC)</p>
                                        </td>
                                        <td>
                                            <p>
                                                DEL/GND/TWR/APP/<strong>*CTR</strong></p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p>
                                                Center Controller (ACC) o superior</p>
                                        </td>
                                        <td>
                                            <p>
                                                DEL/GND/TWR/APP/CTR</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <small>Tabla 1. Rangos Mínimos</small>
                        </div>


                        <p>
                            Nota: Debido a la estructura de los FRA, algunas posiciones permiten controladores con rango ADC
                            en dependencias de aproximación y controladores con rango APC en dependencias de Centro. Para
                            optar por estas posiciones, dependiendo del rango el usuario deberá realizar un chequeo
                            adicional al del GCA en la posición solicitada y este se podrá solicitar únicamente después de
                            un mes calendario de haber obtenido el GCA.
                            Adicionalmente, el solicitante deberá:
                        </p>

                        <ul class="text-light">
                            <li>Hablar, escribir y leer los idiomas español e inglés.</li>
                            <li>Conocer los procedimientos locales y cartas de acuerdo aplicables en la división (disponible
                                en el foro de ATC de IVAO Colombia).</li>
                        </ul>



                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
