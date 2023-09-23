<?php
use App\Models\{Team, Training};
use Laravel\Jetstream\Events\TeamMemberAdded;
use Carbon\Carbon;
use Carbon\CarbonInterval;

function clean($string)
{
    $user[""] = str_replace(" ", "-", $string); // Replaces all spaces with hyphens = ".

    return strtolower(preg_replace("/[^a-zA-Z0-9_.]/", "", $string)); // Removes special chars.
}

function staffLogin($data)
{
    $staff = [];
    foreach ($data as $key => $value) {
        $staff[] = $value["id"];
    }

    $staff = implode(",", $staff);
    return $staff;
}

function secondsToHours($seconds, $detail = false)
{
    if ($detail) {
        $value = $seconds;
        $dt = Carbon::now();
        // $days = $dt->diffInDays($dt->copy()->addSeconds($value));
        $hours = $dt->diffInHours(
            $dt->copy()->addSeconds($value)
            // ->subDays($days)
        );
        $minutes = $dt->diffInMinutes(
            $dt
                ->copy()
                ->addSeconds($value)
                // ->subDays($days)
                ->subHours($hours)
        );
        $segs = $dt->diffInSeconds(
            $dt
                ->copy()
                ->addSeconds($value)
                // ->subDays($days)
                ->subHours($hours)
                ->subMinutes($minutes)
        );
        // days($days)
        return CarbonInterval::hours($hours)
            ->minutes($minutes)
            ->seconds($segs)
            ->forHumans();
    } else {
        return Carbon::parse($seconds)->format("H:i");
    }
}

function getRatingTraining($item)
{
    $ratingATC = Training::ratingAtc();
    $ratingPilot = Training::ratingPilot();

    return $item->typetraining == "PILOTO"
        ? $ratingPilot[$item->rating]
        : $ratingATC[$item->rating];
}

function getStatusTraining($status)
{
    $response = "";
    switch ($status) {
        case 1:
            $response = "Created";
            break;
        case 2:
            $response = "Assigned";
            break;
        case 3:
            $response = "Finished";
            break;
        case 4:
            $response = "Cancelled";
            break;
    }
    return $response;
}

function isStaff($user)
{
    $roles = explode(",", $user->staff);
    $esStaff = false;

    foreach ($roles as $rol) {
        $staffdivision = substr($rol, 0, 3);
        // dd($staffdivision);
        if ($staffdivision == env("APP_DIVISION") . "-") {
            $esStaff = true;
            break;
        }
    }

    return $esStaff;
}

function getUserLocal()
{
    /**
     * Para pruebas en local
     */
    $user["vid"] = "123455";
    $user["firstname"] = "User";
    $user["lastname"] = "Local";
    $user["rating"] = "4";
    $user["ratingatc"] = "4";
    $user["ratingpilot"] = "4";
    $user["division"] = "CO";
    $user["country"] = "CO";
    $user["staff"] = [
        env("APP_DIVISION") . "-PRC",
        env("APP_DIVISION") . "-FOC",
        // env("APP_DIVISION") . "-PRA1",
        "SM3",
        env("APP_DIVISION") . "-WM",
    ];
    // $user["staff"] = [];
    $user["va_staff_ids"] = [23141, 23144, 23146, 23162, 23165];
    $user["va_member_ids"] = [23141, 23144, 23146, 23162, 23165, 23182];

    return $user;
}

function syncTeams($user)
{
    $roles = explode(",", $user->staff);

    $hq = [env("APP_DIVISION") . "-DIR", env("APP_DIVISION") . "-ADIR"];
    $specialoperaciones = [
        env("APP_DIVISION") . "-SOC",
        env("APP_DIVISION") . "-SOAC",
        env("APP_DIVISION") . "-SOA1",
    ];
    $operacionescoordinacion = [
        env("APP_DIVISION") . "-FOC",
        env("APP_DIVISION") . "-FOAC",
    ];
    $webmaster = [
        env("APP_DIVISION") . "-WM",
        env("APP_DIVISION") . "-AWM",
        env("APP_DIVISION") . "-WMA1",
        env("APP_DIVISION") . "-WMA2",
    ];
    $operacionesATC = [
        env("APP_DIVISION") . "-AOC",
        env("APP_DIVISION") . "-AOAC",
    ];
    $entrenamiento = [
        env("APP_DIVISION") . "-TC",
        env("APP_DIVISION") . "-TAC",
        env("APP_DIVISION") . "-TA1",
        env("APP_DIVISION") . "-TA2",
        env("APP_DIVISION") . "-TA3",
        env("APP_DIVISION") . "-TA4",
        env("APP_DIVISION") . "-TA5",
        env("APP_DIVISION") . "-TA6",
        env("APP_DIVISION") . "-TA7",
        env("APP_DIVISION") . "-TA8",
        env("APP_DIVISION") . "-TA9",
        env("APP_DIVISION") . "-T01",
        env("APP_DIVISION") . "-T02",
        env("APP_DIVISION") . "-T03",
        env("APP_DIVISION") . "-T04",
        env("APP_DIVISION") . "-T05",
    ];
    $miembros = [env("APP_DIVISION") . "-MC", env("APP_DIVISION") . "-MAC"];
    $eventos = [
        env("APP_DIVISION") . "-EC",
        env("APP_DIVISION") . "-EAC",
        env("APP_DIVISION") . "-EA1",
        env("APP_DIVISION") . "-EA2",
    ];
    $relaciones = [
        env("APP_DIVISION") . "-PRC",
        env("APP_DIVISION") . "-PRAC",
        env("APP_DIVISION") . "-PRA1",
        env("APP_DIVISION") . "-PRA2",
    ];

    $departamentos = [
        "hq" => $hq,
        "specialoperaciones" => $specialoperaciones,
        "operacionescoordinacion" => $operacionescoordinacion,
        "webmaster" => $webmaster,
        "operacionesATC" => $operacionesATC,
        "entrenamiento" => $entrenamiento,
        "miembros" => $miembros,
        "eventos" => $eventos,
        "relaciones" => $relaciones,
    ];

    /**
     * validar todos los team y verificar si tiene el rol actual,
     * si no lo tiene y esta asignado debe eliminar del team, sino lo tiene y el actual rol
     * debe asignarse
     */

    $teamstaff = Team::find(1);
    $teammiembros = Team::find(2);
    $teameventos = Team::find(3);
    $teamentrenamiento = Team::find(4);
    $teamoperacionesatc = Team::find(5);
    $teamoperacionesespeciales = Team::find(6);
    $teamoperacionesvuelo = Team::find(7);
    $teamrelaciones = Team::find(8);
    $teamhq = Team::find(9);
    $teamwebmaster = Team::find(10);
    $teamusuario = Team::find(11);

    $inteamstaff = $teamstaff->hasUser($user);
    $inteammiembros = $teammiembros->hasUser($user);
    $inteameventos = $teameventos->hasUser($user);
    $inteamentrenamiento = $teamentrenamiento->hasUser($user);
    $inteamoperacionesatc = $teamoperacionesatc->hasUser($user);
    $inteamoperacionesespeciales = $teamoperacionesespeciales->hasUser($user);
    $inteamoperacionesvuelo = $teamoperacionesvuelo->hasUser($user);
    $inteamrelaciones = $teamrelaciones->hasUser($user);
    $inteamhq = $teamhq->hasUser($user);
    $inteamwebmaster = $teamwebmaster->hasUser($user);
    $inteamusuario = $teamusuario->hasUser($user);

    if (!$inteamusuario) {
        $teamusuario->users()->attach($user, ["role" => "estandar"]);
        $user->switchTeam($teamusuario);
    }

    $departamentosAsignado = [];
    //revisamos en cual departamento esta agregado...usando true y false
    foreach ($departamentos as $key => $departamento) {
        foreach ($departamento as $key2 => $item) {
            if (in_array($item, $roles)) {
                $departamentosAsignado[$key] = true;
                break;
            } else {
                $departamentosAsignado[$key] = false;
            }
        }
    }

    //asignamos los departamentos y quitamos los que no pertecene, mediante el array del paso anterior
    foreach ($departamentosAsignado as $key => $item) {
        switch ($key) {
            case "hq":
                if ($item) {
                    if (!$inteamhq) {
                        $teamhq->users()->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teamhq, $user);
                    }
                } else {
                    if ($inteamhq) {
                        $teamhq->users()->detach($user);
                    }
                }
                break;
            case "specialoperaciones":
                if ($item) {
                    if (!$inteamoperacionesespeciales) {
                        $teamoperacionesespeciales
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch(
                            $teamoperacionesespeciales,
                            $user
                        );
                    }
                } else {
                    if ($inteamoperacionesespeciales) {
                        $teamoperacionesespeciales->users()->detach($user);
                    }
                }
                break;
            case "operacionescoordinacion":
                if ($item) {
                    if (!$inteamoperacionesvuelo) {
                        $teamoperacionesvuelo
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teamoperacionesvuelo, $user);
                    }
                } else {
                    if ($inteamoperacionesvuelo) {
                        $teamoperacionesvuelo->users()->detach($user);
                    }
                }
                break;
            case "webmaster":
                if ($item) {
                    if (!$inteamwebmaster) {
                        $teamwebmaster
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teamwebmaster, $user);
                    }
                } else {
                    if ($inteamwebmaster) {
                        $teamwebmaster->users()->detach($user);
                    }
                }
                break;
            case "operacionesATC":
                if ($item) {
                    if (!$inteamoperacionesatc) {
                        $teamoperacionesatc
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teamoperacionesatc, $user);
                    }
                } else {
                    if ($inteamoperacionesatc) {
                        $teamoperacionesatc->users()->detach($user);
                    }
                }
                break;
            case "entrenamiento":
                if ($item) {
                    if (!$inteamentrenamiento) {
                        $teamentrenamiento
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teamentrenamiento, $user);
                    }
                } else {
                    if ($inteamentrenamiento) {
                        $teamentrenamiento->users()->detach($user);
                    }
                }
                break;
            case "miembros":
                if ($item) {
                    if (!$inteammiembros) {
                        $teammiembros
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teammiembros, $user);
                    }
                } else {
                    if ($inteammiembros) {
                        $teammiembros->users()->detach($user);
                    }
                }
                break;
            case "eventos":
                if ($item) {
                    if (!$inteameventos) {
                        $teameventos
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teameventos, $user);
                    }
                } else {
                    if ($inteameventos) {
                        $teameventos->users()->detach($user);
                    }
                }
                break;
            case "relaciones":
                if ($item) {
                    if (!$inteamrelaciones) {
                        $teamrelaciones
                            ->users()
                            ->attach($user, ["role" => "admin"]);
                        TeamMemberAdded::dispatch($teamrelaciones, $user);
                    }
                } else {
                    if ($inteamrelaciones) {
                        $teamrelaciones->users()->detach($user);
                    }
                }
                break;
        }
    } //end foreach

    /** Validamos si esta dentro del equipo actual **/
    // $temcurrent->hasUser($user) : bool
    $temcurrent = Team::find($user->currentTeam->id);
    if (!$temcurrent->hasUser($user)) {
        $user->switchTeam($teamusuario);
    }
}

?>
