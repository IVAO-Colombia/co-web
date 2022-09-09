<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $staff = Team::create([
            "name" => "Staff",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $miembros = Team::create([
            "name" => "Departamento de Miembros",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $eventos = Team::create([
            "name" => "Departamento de Eventos",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $entrenamientos = Team::create([
            "name" => "Departamento de Entrenamiento",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $operacionesatc = Team::create([
            "name" => "Departamento de Operaciones ATC",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $especiales = Team::create([
            "name" => "Departamento de Operaciones Especiales",
            "user_id" => 123456,
            "personal_team" => false,
        ]);
        $operacionesvuelo = Team::create([
            "name" => "Departamento de Operaciones de Vuelo",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $relacionespublicas = Team::create([
            "name" => "Departamento de Relaciones publicas",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $hq = Team::create([
            "name" => "HQ",
            "user_id" => 123456,
            "personal_team" => false,
        ]);
        $hq = Team::create([
            "name" => "Webmaster",
            "user_id" => 123456,
            "personal_team" => false,
        ]);

        $usuarios = Team::create([
            "name" => "Usuarios",
            "user_id" => 123456,
            "personal_team" => false,
        ]);
    }
}
