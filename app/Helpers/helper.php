<?php
use App\Models\Team;

function clean($string)
{
    $user[""] = str_replace(" ", "-", $string); // Replaces all spaces with hyphens = ".

    return strtolower(preg_replace("/[^a-zA-Z0-9_.]/", "", $string)); // Removes special chars.
}

function getUserLocal()
{
    $user["vid"] = "123457";
    $user["firstname"] = "User";
    $user["lastname"] = "Local";
    $user["rating"] = "4";
    $user["ratingatc"] = "4";
    $user["ratingpilot"] = "4";
    $user["division"] = "CO";
    $user["country"] = "CO";
    $user["staff"] = ["CO-EA2", "CO-PRA1", "SM3", "CO-AWM"];
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

    foreach ($teams as $team) {
        switch ($rol) {
            case "CO-EA2":
                # code...
                break;

            default:
                $teamusers = Team::find(10);
                if (!$team->hasUser($user)) {
                    $teamusers->users()->attach($user, ["role" => "Editor"]);
                    $user->switchTeam($teamusers);
                }

                break;
        }
    }
}
?>
