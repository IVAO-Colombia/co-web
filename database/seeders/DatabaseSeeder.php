<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Database\Seeders\TeamSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        $this->call(TeamSeeder::class);
        DB::unprepared(file_get_contents(__DIR__ . "/airports.sql"));
    }
}
