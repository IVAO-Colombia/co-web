<?php

namespace Database\Seeders;

use App\Models\Departments;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Departments::factory()->create([
            "department_id" => "sop",
            "title" => "Operaciones Especiales",
            "team_id" => "6", // Numero de TEAM
            "description" =>
                "Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, corrupti harum, eos sit ut autem recusandae ullam quis debitis nesciunt magni saepe earum obcaecati eligendi optio ipsam natus tempora ea? Lorem ipsum dolor sit amet consectetur adipisicing elit. Veritatis, corrupti harum, eos sit ut autem recusandae ullam quis debitis nesciunt magni saepe earum obcaecati eligendi optio ipsam natus tempora ea?",
        ]);
    }
}
