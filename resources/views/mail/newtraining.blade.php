@component('mail::message')
    <!-- prettier-ignore-start -->
# New training request

Recibimos una nueva solicitud, para asignar ingresa a la plataforma de staff.



**Miembro:** {{$training->member->firstname}} {{$training->member->lastname}} <br />
**VID:** {{$training->member->id}} <br />
**Tipo entrenamiento:** {{$training->typetraining}} <br />

**Rango:** {{  getRatingTraining($training) }} <br />

**Fecha Solicitud:** {{$training->created_at->locale('es')->isoFormat('LLL') }}z


@component('mail::button', ['url' => route("dashboard")])
    Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
<!-- prettier-ignore-end -->
@endcomponent
