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


            <div class="row justify-center flex">
                <div class="content col-md-10 my-2 text-left ">

                    <div class="tag-box tag-box-v1 margin-bottom-40 heading-text heading-light font-light">
                        <p>The training department informs the interested parties about the modification in the division's
                            regulations regarding the
                            Guest Controller Approval or GCA for its acronym. IVAO Colombia has been under the program since
                            2018 under regulations T.2, T.2.1, T2.2.2 and T.2.3.

                        </p>



                        <h2>Definitions</h2>



                        <p>
                            <b>GCA:</b> Acronym for Guest Controller Approval, it is an approval that allows members of
                            other divisions to control in Colombian airspace.
                            <br>

                            <b>FRA:</b> Facility Rating Assignments, establishes the minimum requirements to control a
                            specific ATC facility.
                        </p>

                        <h2 class=" ">Requirements</h2>

                        <p>
                            The Colombian division expects GCA applicants to have a rank that is equivalent to the position
                            they wish to control.
                            For this reason, only users with an ADC (Aerodrome Controller) rank or higher will be
                            considered. The following table shows the positions that can be covered according to the rank:
                        </p>

                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <p>
                                                <strong>Rating</strong>
                                            </p>
                                        </td>
                                        <td>
                                            <p>
                                                <strong>ATC Facility</strong>
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
                                                Center Controller (ACC) or higher</p>
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

                        <br>
                        <p>
                            <b>Note:</b> Due to the structure of the FRA, some positions allow ADC-ranked controllers in
                            approach dependencies and APC-ranked controllers in Center dependencies. To opt for these
                            positions, depending on the rank, the user must perform a check in addition to the GCA in the
                            requested position and this can be requested only after one calendar month of having obtained
                            the GCA.
                        </p>
                        <ul class="list-disc ml-4 pl-4">
                            <li>Speak, write, and read both English and Spanish.</li>
                            <li>Conocer los procedimientos locales y cartas de acuerdo aplicables en la división (disponible
                                en el foro de ATC de IVAO Colombia).</li>
                        </ul>

                        <br>
                        <h2 class=" ">Process for obtaining the GCA</h2>
                        <p>
                            A continuación, se describen los pasos a seguir para el proceso de solicitud, entrenamiento,
                            cheques y posterior aprobación del GCA en la división colombiana.
                        </p>

                        <br>

                        <h4 class=" ">Request:</h4>
                        <p>
                            To apply for Guest Controller Approval the user must send an email to <a
                                href="mailto:co-tc@ivao.aero" class="text-blue-800 font-semibold">co-tc@ivao.aero</a> and
                            <a href="mailto:co-tac@ivao.aero" class="text-blue-800 font-semibold">co-tac@ivao.aero</a>
                            with subject “GCA Request VID XXXXXX” and include in the message:
                        </p>

                        <ul class="list-disc ml-4 pl-4">

                            <li>Name and Last name</li>
                            <li>IVAO VID</li>
                            <li>Explicación breve de por qué solicita el GCA en Colombia.</li>
                        </ul>

                        <br>

                        <h4 class=" ">Training:</h4>
                        <p>
                            Once the request has been sent, the training department will assign a trainer to clarify doubts
                            about local
                            regulations and procedures through a theoretical session. Subsequently, practical training will
                            be carried out in the unit where the control will be carried out.
                            <br><br>
                            Cada una de estas secciones se realizará de la siguiente manera:
                        </p>

                        <br>
                        <ul class="list-disc ml-4 pl-4">

                            <li>
                                <p>
                                    <b>Sesión teórica:</b> It comprises a talk with the trainer completely focused on local
                                    regulations; the trainer will introduce the main points of the Colombian regulations and
                                    will resolve any concerns that may arise during the reading process of the GCA
                                    applicant. The session should not last more than 60 minutes.
                                    <br><br>
                                    <b> Nota: </b>
                                    Puesto que el aspirante ya cuenta con el rango en la red, el entrenamiento bajo
                                    ninguna medida deberá encaminarse a fundamentos OACI.

                                </p>
                            </li>
                            <li>
                                <b>Sesión práctica:</b>
                                Tendrá una duración máxima de 90 minutos, en los que se comprende el
                                tiempo de control y el destinado para las correspondientes correcciones y/o sugerencias del
                                entrenador.
                            </li>

                        </ul>
                        <br>
                        <p>
                            Es importante resaltar que las sesiones de entrenamiento son solamente una herramienta propuesta
                            por la división, no es obligatoria durante el proceso.
                            <br><br>
                            <b> Nota: </b>
                            Solamente se debe omitir el entrenamiento bajo solicitud del aspirante; las sesiones se
                            agendarán directamente con el entrenador por medio de los canales oficiales o cualquier otro
                            siempre y cuando sea acordado por ambas partes.

                        </p>


                        <h4 class=" ">Final check:</h4>
                        <p>
                            Al igual que el entrenamiento, el chequeo final se dividirá en dos partes, una teórica y una
                            práctica que se llevarán a cabo de la siguiente manera:
                        </p>
                        <br>
                        <ul class="list-disc ml-4 pl-4">

                            <li>
                                <p>
                                    <b>Sesión teórica:</b> Estará compuesta de una evaluación teórica vía comunicación
                                    verbal directa, en esta se tratará principalmente lo relacionado con las regulaciones
                                    locales, sin embargo, si el entrenador lo ve conveniente puede realizar preguntas sobre
                                    procedimientos de control en general con base en el rango del aspirante. No debe durar
                                    más de 45 minutos, debe hacerse antes de la parte práctica pero no es mandatorio que
                                    ambas sesiones se realicen el mismo día.

                                </p>
                            </li>
                            <li>
                                <b>Practical session:</b>
                                <p>
                                    Esta sesión se llevará a cabo en la dependencia de Torre El Dorado (SKBO_TWR) en caso de
                                    que
                                    el solicitante del GCA aspire a controlar en las diferentes torres del país. Se
                                    realizará en
                                    aproximación Bogotá (SKBO_APP) si el aspirante es rango APC o superior, busca controlar
                                    en
                                    las dependencias de aproximación del país o cuenta con su GCA por un periodo no menor a
                                    30
                                    días calendario. Finalmente, tomará lugar en Bogotá Control (SKED_CTR) si el aspirante
                                    cuenta con rango ACC o superior. La parte práctica no deberá durar más de 120 minutos,
                                    comprendida la sesión de control y los comentarios finales por parte del evaluador.
                                    <br> <br>
                                    <b> Nota:</b>
                                    Miembros con el rango APC que ostenten el GCA no pueden aplicar al chequeo de control de
                                    dependencias de área.
                                </p>

                            </li>

                        </ul>
                        <p>
                            Es importante aclarar que no es un examen del rango del aspirante, es un chequeo de técnicas de
                            control y conocimiento de regulaciones locales, por lo que no se maneja un puntaje, el resultado
                            es la valoración del evaluador.
                        </p>
                    </div>
                </div>



            </div>
        </div>
    </section>
@endsection
