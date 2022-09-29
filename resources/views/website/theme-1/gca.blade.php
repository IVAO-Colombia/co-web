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
                <div class="col-md-12 my-2">

                    <div class="tag-box tag-box-v1 margin-bottom-40" id="webload">

                        <p style="text-align:justify">La División <strong>restringe</strong> el control de sus
                            posiciones de control únicamente a los usuarios de la misma. Usuarios de otras divisiones
                            también pueden controlar posiciones de control españolas si reciben la aprobación de TC/TAC a
                            través del programa <em>Guest Controller Approval</em>.</p>

                        <p style="text-align:justify">&nbsp;</p>

                        <h2 style="text-align:justify">Requisitos para la solicitud</h2>

                        <p style="text-align:justify">Si eres un usuario registrado en otra división y estás interesado en
                            controlar posiciones de control españolas, deberás <strong>cumplir todos los requisitos</strong>
                            que se detallan a continuación:</p>

                        <ul>
                            <li style="text-align:justify">Tener como mínimo la habilitación APC - Approach Controller.
                            </li>
                            <li style="text-align:justify">Poder proporcionar el servicio en idioma español e inglés,
                                utilizando fraseología estándar europea.</li>
                            <li style="text-align:justify">Conocer los procedimientos de control divisionales y locales,
                                publicados en la página web del Dpto. ATC y FIRes, respectivamente.</li>
                            <li style="text-align:justify">Demostrar amplio conocimiento en la construcción del espacio
                                aéreo y zona de responsabilidad de la posición a controlar.</li>
                            <li style="text-align:justify">No haber solicitado ningún cambio de división desde la española
                                a cualquier otra en los últimos 12 meses*.</li>
                            <li style="text-align:justify">No haber solicitado el GCA en los últimos 6 meses*.</li>
                            <li style="text-align:justify">Demostrar buen comportamiento de acuerdo a la normativa y
                                filosofía de IVAO.</li>
                        </ul>

                        <p>A la hora de completar el segundo y tercer requisito, la División de España ofrece la figura de
                            GCA Temporal explicada a continuación.</p>

                        <p style="text-align:justify"><em>*En casos justificados TC/TAC o la dirección de la división podrá
                                otorgar el GCA aunque no se cumplan los requisitos marcados con un asterisco.</em></p>

                        <p style="text-align:justify">&nbsp;</p>

                        <h2>GCA Temporal (T-GCA)</h2>

                        <p>La figura del GCA Temporal permite a los usuarios que estén planeando obtener su habilitación de
                            controlador invitado en IVAO España, acceder a los servicios de Discord divisionales. De manera
                            que puedan empezar a familiarizarse con procedimientos de control locales, y con el idioma
                            oficial de la División.</p>

                        <p>Durante este permiso temporal, se proporcionará acceso a Discord, con un rol adecuado
                            (<strong>T-GCA</strong>).</p>

                        <p><strong>IMPORTANTE!</strong> Si el candidato a solicitar el GCA no conoce los procedimientos
                            locales o tiene dificultades con el idioma español se recomienda solicitar el GCA Temporal antes
                            de solicitar el GCA definitivo.</p>

                        <h3><strong>Requisitos para la obtención del GCA&nbsp;temporal:</strong></h3>

                        <ul>
                            <li>Cumplir con todos los requisitos fijados por el departamento de Formación de IVAO España
                                para la obtención de la habilitación GCA, excluyendo el conocimiento de los procedimientos
                                locales y el idioma español.</li>
                            <li>Aceptación de las normas y regulaciones del Discord de IVAO-ES.</li>
                            <li>Dicho permiso no se extenderá por más de 3 meses. En caso de haber excedido dicho periodo
                                sin haber obtenido el GCA definitivo, se retirarán los permisos de acceso al Discord, no
                                pudiendo volver a solicitarse en los siguientes 3 meses. En el segundo permiso de invitado y
                                posteriores, la duración máxima del GCA Temporal se verá reducido a 1 mes.</li>
                        </ul>

                        <p>Si durante el periodo de invitación temporal se produce alguna violación de las normas de Discord
                            o de la red, se revocará de manera inmediata la invitación.</p>

                        <p>&nbsp;</p>

                        <h2 style="text-align:justify">Solicitud del GCA</h2>

                        <p>La solicitud se hace desde el <a
                                href="https://ivao.es/?module=community&amp;page=contacto">formulario de contacto</a>, en
                            la parte superior de la página web. Una vez que la solicitud haya sido recibida serás contactado
                            por un examinador del Dpto. de Formación en caso de que cumplas con los requisitos descritos
                            arriba. Fijarás una fecha con él para realizar una <strong>verificación de competencia</strong>
                            en la posición de control que elijas. Esta sesión no es un examen, sino&nbsp;una sesión de
                            control normal donde se observa tu rendimiento en la posición de control, habilidades y
                            fraseología.</p>

                        <h2 style="text-align:justify">&nbsp;</h2>

                        <h2 style="text-align:justify">Aprobación del GCA</h2>

                        <p style="text-align:justify">Después de la sesión, el responsable te comunicará por e-mail si tu
                            GCA está aprobado o no.</p>

                        <ul>
                            <li style="text-align:justify">En caso de que esté aprobado, éste quedará anotado en tu ficha
                                personal:</li>
                        </ul>

                        <p style="text-align:center">
                            <a href="https://files.ivao.es/WebPage/Imagenes/Formacion/2017/GCA/gca.png" target="_blank"><img
                                    src="https://files.ivao.es/WebPage/Imagenes/Formacion/2017/GCA/gca.png"></a>
                        </p>

                        <ul>
                            <li style="text-align:justify">En caso de que no esté aprobado, el responsable te dirá el
                                motivo y podrás volver a solicitarlo tras 6 meses.</li>
                        </ul>

                        <h2 style="text-align:justify">&nbsp;</h2>

                        <h2 style="text-align:justify">Mantenimiento del GCA</h2>

                        <p style="text-align:justify">Tu GCA será <strong>anulado </strong>a menos que&nbsp;cumplas los
                            siguientes requisitos:</p>

                        <ul>
                            <li style="text-align:justify">Controlar 20 horas cada 6 meses en una dependencia de control
                                española.</li>
                            <li style="text-align:justify">No tener ninguna suspesión en IVAO durante la posesión del GCA.
                            </li>
                            <li style="text-align:justify">Hacer buen uso del GCA: no incumplir la normativa de IVAO,
                                proveer un servicio de calidad y de acuerdo a los procedimientos divisionales y locales.
                            </li>
                            <li style="text-align:justify">Mantener un comportamiento adecuado dentro de los canales de
                                comunicación de la División de España.</li>
                        </ul>

                        <p style="text-align:justify">No obstante, los responsables del Dpto. de Formación (TC/TAC) o la
                            Dirección de la División podrán anular el GCA en cualquier momento de forma justificada.</p>

                        <p style="text-align:justify">&nbsp;</p>

                        <h2 style="text-align: justify;">Participación en actividades oficiales</h2>

                        <p style="text-align:justify">Un usuario GCA podrá participar en las actividades oficiales de la
                            División (Eventos, quedadas, etc) con la única limitación que los cuadrantes serán configurados
                            con total prioridad hacia los miembros de la División.</p>
                        <p></p>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
