<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = [
            [
              "name" => "Juan Perez",
              "email" => "juan@gmail.com",
            ],
            [
              "name" => "Luis Hernandez",
              "email" => "luis@gmail.com",
            ],
            [
              "name" => "Victor Sanchego",
              "email" => "victor@gmail.com",
            ],
            [
              "name" => "Eduardo Josias",
              "email" => "eduardo@gmail.com",
            ],
        ];

        foreach($users as $index => $u){
            $users[$index]["password"] = Hash::make("1234");
            $users[$index]["created_at"] = now();
            $users[$index]["updated_at"] = now();
        }

        DB::table('users')->insert($users);
    }
}
