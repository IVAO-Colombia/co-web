<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Team, User};

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::find(1);

        // $user->switchTeam(
        //     $team = $user->ownedTeams()->create([
        //         "name" => $input["name"],
        //         "personal_team" => false,
        //     ])
        // );

        $staff = $user->ownedTeams()->create([
            "name" => "Staff",
            "personal_team" => false,
        ]);

        $user->switchTeam($staff);

        $miembros = $user->ownedTeams()->create([
            "name" => "Departamento de Miembros",
            "personal_team" => false,
        ]);

        $eventos = $user->ownedTeams()->create([
            "name" => "Departamento de Eventos",
            "personal_team" => false,
        ]);

        $entrenamientos = $user->ownedTeams()->create([
            "name" => "Departamento de Entrenamiento",
            "personal_team" => false,
        ]);

        $operacionesatc = $user->ownedTeams()->create([
            "name" => "Departamento de Operaciones ATC",
            "personal_team" => false,
        ]);

        $especiales = $user->ownedTeams()->create([
            "name" => "Departamento de Operaciones Especiales",
            "personal_team" => false,
        ]);
        $operacionesvuelo = $user->ownedTeams()->create([
            "name" => "Departamento de Operaciones de Vuelo",
            "personal_team" => false,
        ]);

        $relacionespublicas = $user->ownedTeams()->create([
            "name" => "Departamento de Relaciones publicas",
            "personal_team" => false,
        ]);

        $hq = $user->ownedTeams()->create([
            "name" => "HQ",
            "personal_team" => false,
        ]);
        $hq = $user->ownedTeams()->create([
            "name" => "Webmaster",
            "personal_team" => false,
        ]);

        $usuarios = $user->ownedTeams()->create([
            "name" => "Usuarios",
            "personal_team" => false,
        ]);
    }
}
