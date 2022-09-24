<?php
use App\Models\Team;
use Laravel\Jetstream\Events\TeamMemberAdded;

function clean($string)
{
    $user[""] = str_replace(" ", "-", $string); // Replaces all spaces with hyphens = ".

    return strtolower(preg_replace("/[^a-zA-Z0-9_.]/", "", $string)); // Removes special chars.
}

function isStaff($user)
{
    $roles = explode(",", $user->staff);
    $esStaff = false;

    foreach ($roles as $rol) {
        $staffdivision = substr($rol, 0, 3);
        if ($staffdivision === "CO-") {
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
    $user["vid"] = "123457";
    $user["firstname"] = "User";
    $user["lastname"] = "Local";
    $user["rating"] = "4";
    $user["ratingatc"] = "4";
    $user["ratingpilot"] = "4";
    $user["division"] = "CO";
    $user["country"] = "CO";
    $user["staff"] = ["CO-EA2", "CO-PRA1", "SM3", "CO-WM"];
    // $user["staff"] = [];
    $user["va_staff_ids"] = [23141, 23144, 23146, 23162, 23165];
    $user["va_member_ids"] = [23141, 23144, 23146, 23162, 23165];

    return $user;
}

function syncTeams($user)
{
    $roles = explode(",", $user->staff);

    $hq = ["CO-DIR", "CO-ADIR"];
    $specialoperaciones = ["CO-SOC", "CO-SOAC", "CO-SOA1"];
    $operacionescoordinacion = ["CO-FOC", "CO-FOAC"];
    $webmaster = ["CO-WM", "CO-AWM", "CO-WMA1"];
    $operacionesATC = ["CO-AOC", "CO-AOAC"];
    $entrenamiento = [
        "CO-TC",
        "CO-TAC",
        "CO-TA1",
        "CO-TA2",
        "CO-TA3",
        "CO-TA4",
        "CO-TA5",
        "CO-TA6",
        "CO-TA7",
        "CO-TA8",
        "CO-TA9",
        "CO-T01",
    ];
    $miembros = ["CO-MC", "CO-MAC"];
    $eventos = ["CO-EC", "CO-EAC", "CO-EA1", "CO-EA2"];
    $relaciones = ["CO-PRC", "CO-PRAC", "CO-PRA1", "CO-PRA2"];

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
    }

    $user->switchTeam($teamusuario);
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
}

?>
