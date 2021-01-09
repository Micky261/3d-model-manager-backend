<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table("users")->insert([
            "name" => "admin",
            "email" => "a@dm.in",
            "password" => Hash::make("AdM1n")
        ]);
    }
}
