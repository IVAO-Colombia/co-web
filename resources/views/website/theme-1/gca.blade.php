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

                        <br>
                        <p>
                            <b>Nota:</b> Debido a la estructura de los FRA, algunas posiciones permiten controladores con
                            rango ADC
                            en dependencias de aproximación y controladores con rango APC en dependencias de Centro. Para
                            optar por estas posiciones, dependiendo del rango el usuario deberá realizar un chequeo
                            adicional al del GCA en la posición solicitada y este se podrá solicitar únicamente después de
                            un mes calendario de haber obtenido el GCA.
                            Adicionalmente, el solicitante deberá:
                        </p>

                        <ul class="list-disc ml-4 pl-4">
                            <li>Hablar, escribir y leer los idiomas español e inglés.</li>
                            <li>Conocer los procedimientos locales y cartas de acuerdo aplicables en la división (disponible
                                en el foro de ATC de IVAO Colombia).</li>
                        </ul>

                        <br>
                        <h2 class=" ">Proceso de obtención del GCA</h2>
                        <p>
                            A continuación, se describen los pasos a seguir para el proceso de solicitud, entrenamiento,
                            cheques y posterior aprobación del GCA en la división colombiana.
                        </p>

                        <br>

                        <h4 class=" ">Solicitud</h4>
                        <p>
                            Para aplicar al Guest Controller Approval el usuario deberá enviar un correo a <a
                                href="mailto:co-tc@ivao.aero" class="text-blue-800 font-semibold">co-tc@ivao.aero</a> y
                            <a href="mailto:co-tac@ivao.aero" class="text-blue-800 font-semibold">co-tac@ivao.aero</a> con
                            asunto
                            <b>“Solicitud GCA VID XXXXXX”</b> e incluir en el mensaje:
                        </p>

                        <ul class="list-disc ml-4 pl-4">

                            <li>Nombre y apellido</li>
                            <li>IVAO VID</li>
                            <li>Explicación breve de por qué solicita el GCA en Colombia.</li>
                        </ul>

                        <br>

                        <h4 class=" ">Entrenamiento</h4>
                        <p>
                            Una vez enviada la solicitud el departamento de entrenamiento asignará un entrenador con el fin
                            de aclarar dudas sobre la reglamentación y procedimientos locales por medio de una sesión
                            teórica. Posteriormente, se realizará un entrenamiento práctico en la dependencia en que se
                            llevará a cabo el chequeo.
                            <br><br>
                            Cada una de estas secciones se realizará de la siguiente manera:
                        </p>

                        <br>
                        <ul class="list-disc ml-4 pl-4">

                            <li>
                                <p>
                                    <b>Sesión teórica:</b> Comprende una charla con el entrenador completamente enfocada a
                                    regulaciones locales; el entrenador, expondrá la introducción a los principales puntos
                                    de las regulaciones colombianas y resolverá las inquietudes que puedan surgir durante el
                                    proceso de lectura el solicitante del GCA. La sesión no debe durar más de 60 minutos.
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


                        <h4 class=" ">Chequeo final</h4>
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
                                <b>Sesión práctica:</b>
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

                        <h4 class=" "> Resultado Final</h4>
                        <p>
                            Al finalizar el chequeo el evaluador informará al aspirante si el resultado fue o no satisfactorio, si lo fue, 
                            el examinador notificará a los coordinadores del departamento de entrenamiento el resultado para que el GCA se haga efectivo, 
                            esto se realizará en un plazo máximo de 15 días calendario a partir del día siguiente del chequeo.
                            <br><br>
                            Por parte de la no aprobación del chequeo, el aspirante deberá esperar 15 días calendario a partir del día siguiente del chequeo para solicitar una nueva evaluación, 
                            en este caso se omite la parte de entrenamiento, esta quedará a decisión del entrenador si decide o no brindarla posterior al chequeo insatisfactorio.
                            <br><br>
                            <b>Nota:</b> El aspirante deberá esperar el correo de confirmación del GCA por parte de la coordinación del departamento de entrenamiento para poder conectarse en las 
                            dependencias del país, si incurre en la falta de esta regulación, el miembro no obtendrá su GCA y deberá esperar 90 días calendario para enviar su solicitud de nuevo.    
                        </p>
                        
                        <h4 class=" ">Reglas para mantener el GCA</h4>

                        <ul class="list-disc ml-4 pl-4">
                                <li>
                                    <p>
                                        El usuario poseedor del GCA debe cumplir con mínimo 6 horas de control en un lapso de 30 días calendario, para un total de 18 horas por trimestre. 
                                        En caso de no cumplir con el mínimo de horas trimestrales, el GCA será removido y deberá esperar mínimo 90 días calendario para realizar una nueva solicitud. 
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Si el usuario incumple alguna de las R&R de IVAO el GCA será removido y 
                                        el tiempo para enviar una nueva solicitud no podrá ser menor al doble de la sanción establecida por la red.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Si el usuario no presenta conocimiento adecuando de los procedimientos locales y los estándares de control el GCA será removido, 
                                        el tiempo de espera para una nueva solicitud será determinado por la coordinación del departamento de entrenamiento de la división Colombia y su HQ.
                                    </p>    
                                </li>
                            </ul>
                        <h4 class=" ">Consideraciones finales</h4>
                        <p>
                            El GCA es una herramienta dispuesta por IVAO para aumentar la participación de los miembros e incentivar la relación entre la comunidad, 
                            sin embargo, queda a discreción del departamento de entrenamiento y el HQ divisional la libre remoción de la habilitación.
                            <br><br>
                            Para ex miembros de la división el departamento de entrenamiento, de la mano con el HQ divisional, podrán tomar la decisión de 
                            otorgar el GCA sin necesidad de chequeo; esto siempre cuando el rango con el que aspire a controlar haya sido obtenido en la división colombiana. 
                            <br><br>
                            Así mismo, el departamento de entrenamiento y el HQ divisional tiene la potestad de agregar y remover las habilitaciones a 
                            las demás dependencias con base en su criterio de evaluación. 
                            <br><br>
                            Para miembros con rango APC, poseedores o aspirantes al GCA que estén interesados en controlar las dependencias de control 
                            superior deberán haber obtenido el rango en la división Colombia.
                            <br><br>
                            No es necesario que el aspirante realice el chequeo inicial en la mayor dependencia que le permita su rango, por ejemplo, un miembro con
                            rango ACC puede solicitar el GCA por medio de un chequeo en torre, sin embargo, debe realizar todo el proceso para las demás dependencias.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
